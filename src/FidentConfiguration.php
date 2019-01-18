<?php
namespace Fident\Web;

class FidentConfiguration
{
  private $_publicKey;
  private $_aesKey;
  private $_registerUrl; //Url to register
  private $_loginUrl; //Url to login
  private $_logoutUrl; //Url to logout
  private $_serviceUrl;//URL to manage account

  /**
   * @return mixed
   */
  public function getPublicKey()
  {
    return $this->_publicKey;
  }

  /**
   * @param mixed $publicKey
   *
   * @return FidentConfiguration
   */
  public function setPublicKey($publicKey)
  {
    $this->_publicKey = $publicKey;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getAesKey()
  {
    return $this->_aesKey;
  }

  /**
   * @param mixed $aesKey
   *
   * @return FidentConfiguration
   */
  public function setAesKey($aesKey)
  {
    $this->_aesKey = $aesKey;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getRegisterUrl()
  {
    return $this->_registerUrl;
  }

  /**
   * @param mixed $registerUrl
   *
   * @return FidentConfiguration
   */
  public function setRegisterUrl($registerUrl)
  {
    $this->_registerUrl = $registerUrl;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getLoginUrl()
  {
    return $this->_loginUrl;
  }

  /**
   * @param mixed $loginUrl
   *
   * @return FidentConfiguration
   */
  public function setLoginUrl($loginUrl)
  {
    $this->_loginUrl = $loginUrl;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getLogoutUrl()
  {
    return $this->_logoutUrl;
  }

  /**
   * @param mixed $logoutUrl
   *
   * @return FidentConfiguration
   */
  public function setLogoutUrl($logoutUrl)
  {
    $this->_logoutUrl = $logoutUrl;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getServiceUrl()
  {
    return $this->_serviceUrl;
  }

  /**
   * @param mixed $serviceUrl
   *
   * @return FidentConfiguration
   */
  public function setServiceUrl($serviceUrl)
  {
    $this->_serviceUrl = $serviceUrl;
    return $this;
  }

  public function getReauthUrl()
  {
    return rtrim($this->_serviceUrl, '/') . '/reauthenticate';
  }
}
