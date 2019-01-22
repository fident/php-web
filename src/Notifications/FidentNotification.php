<?php
namespace Fident\Web\Notifications;

use Fident\Web\UserData\UserAttribute;
use Packaged\Helpers\Objects;

class FidentNotification
{
  protected $_id;
  protected $_created;
  protected $_username;
  protected $_attributes;
  protected $_type;

  const SUCCESS_RESPONSE = 'con';

  public static function fromString($rawNotificationData)
  {
    $notification = new static();
    $notificationData = json_decode($rawNotificationData);
    $notification->_id = Objects::property($notificationData, 'ID');
    $notification->_created = Objects::property($notificationData, 'Created');
    $notification->_username = Objects::property($notificationData, 'Username');
    $notification->_type = Objects::property($notificationData, 'Type');
    $notification->_attributes = [];
    foreach(Objects::property($notificationData, 'Attributes', []) as $attr)
    {
      $notification->addAttribute(Objects::property($attr, 'Key'), Objects::property($attr, 'Value'));
    }
    return $notification;
  }

  public function addAttribute($key, $value)
  {
    $this->_attributes[$key] = new UserAttribute($key, $value);
    return $this;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->_id;
  }

  /**
   * @return mixed
   */
  public function getCreated()
  {
    return $this->_created;
  }

  /**
   * @return mixed
   */
  public function getUsername()
  {
    return $this->_username;
  }

  /**
   * @return UserAttribute[]
   */
  public function getAttributes()
  {
    return $this->_attributes;
  }

  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->_type;
  }

}
