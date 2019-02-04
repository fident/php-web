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
   * @param null $returnUrl
   *
   * @return mixed
   */
  public function getRegisterUrl($returnUrl = null)
  {
    return $this->_registerUrl . ($returnUrl ? '?destination=' . $returnUrl : '');
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
   * @param null $returnUrl
   *
   * @return mixed
   */
  public function getLoginUrl($returnUrl = null)
  {
    return $this->_loginUrl . ($returnUrl ? '?destination=' . $returnUrl : '');
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
   * @param null $returnUrl
   *
   * @return mixed
   */
  public function getLogoutUrl($returnUrl = null)
  {
    return $this->_logoutUrl . ($returnUrl ? '?destination=' . $returnUrl : '');
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

  /**
   * @param null $returnUrl
   *
   * @return string
   */
  public function getReauthUrl($returnUrl = null)
  {
    return rtrim($this->_serviceUrl, '/') . '/reauthenticate' . ($returnUrl ? '?destination=' . $returnUrl : '');
  }
}
