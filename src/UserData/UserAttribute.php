<?php
namespace Fident\Web\UserData;

class UserAttribute
{
  protected $_id;
  protected $_key;
  protected $_value;

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * @param mixed $id
   *
   * @return UserAttribute
   */
  public function setId($id)
  {
    $this->_id = $id;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getKey()
  {
    return $this->_key;
  }

  /**
   * @param mixed $key
   *
   * @return UserAttribute
   */
  public function setKey($key)
  {
    $this->_key = $key;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getValue()
  {
    return $this->_value;
  }

  /**
   * @param mixed $value
   *
   * @return UserAttribute
   */
  public function setValue($value)
  {
    $this->_value = $value;
    return $this;
  }

}
