<?php
namespace Fident\Web\Notifications;

class FidentNotification
{
  const SUCCESS_RESPONSE = 'con';

  const DT_USER_UPDATE = 1;

  public static function generate($dataType, $jsonString)
  {
    switch($dataType)
    {
      case self::DT_USER_UPDATE:
        return UserUpdateNotification::fromString($jsonString);
    }
    return null;
  }

}
