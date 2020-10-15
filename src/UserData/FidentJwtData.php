<?php
namespace Fident\Web\UserData;

use Packaged\Helpers\Objects;
use Packaged\Helpers\ValueAs;

class FidentJwtData
{
  protected $_identityId;
  protected $_username;
  protected $_type;
  /**
   * @var UserAttribute[]
   */
  protected $_attributes = [];
  protected $_userAgent;
  protected $_mfa;
  protected $_verified;

  protected $_accountType;
  protected $_expiry;
  protected $_issuedAt;
  protected $_issuer;
  protected $_tokenId;

  public function fromPayload($payload)
  {
    $this->_identityId = Objects::property($payload, 'I');
    $this->_username = Objects::property($payload, 'N');
    $this->_type = Objects::property($payload, 'T');
    $this->_attributes = [];
    foreach(Objects::property($payload, 'A', []) as $attr)
    {
      $attribute = new UserAttribute();
      $attribute->setId(Objects::property($attr, 'I'));
      $attribute->setKey(Objects::property($attr, 'K'));
      $attribute->setValue(html_entity_decode(Objects::property($attr, 'V'), ENT_QUOTES | ENT_XHTML, 'UTF-8'));
      $this->_attributes[$attribute->getKey()] = $attribute;
    }
    $this->_userAgent = Objects::property($payload, 'U');
    $this->_mfa = ValueAs::bool(Objects::property($payload, 'M'));
    $this->_verified = ValueAs::bool(Objects::property($payload, 'V'));
    return $this;
  }

  /**
   * @return mixed
   */
  public function getIdentityId()
  {
    return $this->_identityId;
  }

  /**
   * @return mixed
   */
  public function getUsername()
  {
    return $this->_username;
  }

  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->_type;
  }

  /**
   * @return UserAttribute[]
   */
  public function getAttributes(): array
  {
    return $this->_attributes;
  }

  /**
   * @return mixed
   */
  public function getUserAgent()
  {
    return $this->_userAgent;
  }

  /**
   * @return mixed
   */
  public function hasMfa()
  {
    return !!$this->_mfa;
  }

  /**
   * @return mixed
   */
  public function isVerified()
  {
    return !!$this->_verified;
  }

  /**
   * @return mixed
   */
  public function getAccountType()
  {
    return $this->_accountType;
  }

  /**
   * @param mixed $accountType
   *
   * @return FidentJwtData
   */
  public function setAccountType($accountType)
  {
    $this->_accountType = $accountType;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getExpiry()
  {
    return $this->_expiry;
  }

  /**
   * @param mixed $expiry
   *
   * @return FidentJwtData
   */
  public function setExpiry($expiry)
  {
    $this->_expiry = $expiry;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getIssuedAt()
  {
    return $this->_issuedAt;
  }

  /**
   * @param mixed $issuedAt
   *
   * @return FidentJwtData
   */
  public function setIssuedAt($issuedAt)
  {
    $this->_issuedAt = $issuedAt;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getIssuer()
  {
    return $this->_issuer;
  }

  /**
   * @param mixed $issuer
   *
   * @return FidentJwtData
   */
  public function setIssuer($issuer)
  {
    $this->_issuer = $issuer;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getTokenId()
  {
    return $this->_tokenId;
  }

  /**
   * @param mixed $tokenId
   *
   * @return FidentJwtData
   */
  public function setTokenId($tokenId)
  {
    $this->_tokenId = $tokenId;
    return $this;
  }

  public function getFirstName()
  {
    return $this->getAttributeValue('firstname');
  }

  public function getLastName()
  {
    return $this->getAttributeValue('lastname');
  }

  public function getAttributeValue($key)
  {
    return isset($this->_attributes[$key]) ? $this->_attributes[$key]->getValue() : null;
  }

}
