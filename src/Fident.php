<?php
namespace Fident\Web;

use Fident\Web\Notifications\FidentNotification;
use Fident\Web\UserData\FidentJwtData;
use Packaged\Helpers\Objects;
use Packaged\Helpers\Strings;

class Fident
{
  const JWT_COOKIE_NAME = 'fident-token';
  const JWT_COOKIE_NAME_INSECURE = 'fident-token-ns';

  /**
   * @var FidentConfiguration
   */
  private $_configuration;

  public function __construct(FidentConfiguration $config)
  {
    $this->_configuration = $config;
  }

  public function getConfig(): FidentConfiguration
  {
    return $this->_configuration;
  }

  protected $_verifyCache = [];

  public function verifyJwt(string $rawJwt): bool
  {
    if(empty($rawJwt))
    {
      return false;
    }

    $jwtHash = md5($rawJwt);
    if(isset($this->_verifyCache[$jwtHash]))
    {
      return $this->_verifyCache[$jwtHash];
    }

    $parts = explode('.', $rawJwt, 3);
    if(count($parts) != 3)
    {
      $this->_verifyCache[$jwtHash] = false;
      return $this->_verifyCache[$jwtHash];
    }
    [$head64, $payload64, $sig64] = $parts;
    $header = json_decode(Strings::urlsafeBase64Decode($head64));
    if(!$header || !isset($header->typ) || $header->typ !== 'JWT')
    {
      $this->_verifyCache[$jwtHash] = false;
      return $this->_verifyCache[$jwtHash];
    }
    $key = $this->_configuration->getPublicKey();
    $this->_verifyCache[$jwtHash] = openssl_verify(
        "$head64.$payload64",
        Strings::urlsafeBase64Decode($sig64),
        $key,
        OPENSSL_ALGO_SHA256
      ) === 1;
    return $this->_verifyCache[$jwtHash];
  }

  /**
   * @var FidentJwtData[]
   */
  protected $_dataCache = [];

  public function decodeJwtPayload(string $rawJwt): ?FidentJwtData
  {
    if(empty($rawJwt))
    {
      return null;
    }

    $jwtHash = md5($rawJwt);
    if(isset($this->_dataCache[$jwtHash]))
    {
      return $this->_dataCache[$jwtHash];
    }

    if(!$this->verifyJwt($rawJwt))
    {
      return null;
    }

    $data = new FidentJwtData();
    $this->_dataCache[$jwtHash] = $data;

    [, $payload64,] = explode('.', $rawJwt, 3);
    $payload = json_decode(Strings::urlsafeBase64Decode($payload64));
    $payload->payload = Strings::urlsafeBase64Decode($payload->payload);

    $method = 'AES-256-CFB';
    $ivlen = openssl_cipher_iv_length($method);
    $iv = substr($payload->payload, 0, $ivlen);
    $raw = substr($payload->payload, $ivlen);
    $decrypted = openssl_decrypt($raw, $method, $this->getConfig()->getAesKey(), OPENSSL_RAW_DATA, $iv);
    if($decrypted !== false)
    {
      $data->fromPayload(json_decode(base64_decode($decrypted)));
    }

    $data->setAccountType(Objects::property($payload, 'account_type'));
    $data->setExpiry(Objects::property($payload, 'exp'));
    $data->setIssuedAt(Objects::property($payload, 'iat'));
    $data->setIssuer(Objects::property($payload, 'iss'));
    $data->setTokenId(Objects::property($payload, 'sub'));

    return $data;
  }

  public function decodeNotification($requestBody): ?FidentNotification
  {
    $notification = json_decode($requestBody);
    $data = Objects::property($notification, 'Data', '');
    $sig = Strings::urlsafeBase64Decode(Objects::property($notification, 'Signature', ''));
    if(openssl_verify($data, $sig, $this->getConfig()->getPublicKey(), OPENSSL_ALGO_SHA256))
    {
      return FidentNotification::generate(Objects::property($notification, 'DataType', 1), $data);
    }
    return null;
  }
}
