<?php


namespace Toolbox\Core\Util;


trait MailTrait
{

    public static function eventSend($eventId, array $data)
    {
        \CEvent::Send($eventId, 's1', $data);
    }

}