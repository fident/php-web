<?php
namespace Fident\Web;

use Fident\Web\Notifications\FidentNotification;
use Fident\Web\UserData\FidentJwtData;
use Packaged\Helpers\Objects;

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

  public function verifyJwt(string $rawJwt): bool
  {
    list($header64, $payload64, $sig64) = explode('.', $rawJwt, 3);
    $header = json_decode(self::urlsafeB64Decode($header64));
    if(!$header || !isset($header->typ) || $header->typ !== 'JWT')
    {
      return false;
    }
    $key = $this->_configuration->getPublicKey();
    return openssl_verify("$header64.$payload64", self::urlsafeB64Decode($sig64), $key, OPENSSL_ALGO_SHA256) === 1;
  }

  public function decodeJwtPayload(string $rawJwt): ?FidentJwtData
  {
    $data = new FidentJwtData();

    list(, $payload64,) = explode('.', $rawJwt, 3);
    $payload = json_decode(self::urlsafeB64Decode($payload64));
    $payload->payload = self::urlsafeB64Decode($payload->payload);

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
    $data->setIssuesAt(Objects::property($payload, 'iat'));
    $data->setIssuer(Objects::property($payload, 'iss'));
    $data->setTokenId(Objects::property($payload, 'sub'));

    return $data;
  }

  public function decodeNotification($requestBody): ?FidentNotification
  {
    $notification = json_decode($requestBody);
    $data = Objects::property($notification, 'Data', '');
    $sig = self::urlsafeB64Decode(Objects::property($notification, 'Signature', ''));
    if(openssl_verify($data, $sig, $this->getConfig()->getPublicKey(), OPENSSL_ALGO_SHA256))
    {
      return FidentNotification::generate(Objects::property($notification, 'DataType', 1), $data);
    }
    return null;
  }

  public static function urlsafeB64Decode($input)
  {
    $remainder = strlen($input) % 4;
    if($remainder)
    {
      $padlen = 4 - $remainder;
      $input .= str_repeat('=', $padlen);
    }
    return base64_decode(strtr($input, '-_', '+/'));
  }
}
