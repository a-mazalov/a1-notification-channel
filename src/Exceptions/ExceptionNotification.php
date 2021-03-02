<?php

namespace A1\Channel\Exceptions;

class ExceptionNotification extends \Exception
{
    public static function serviceEmptyResponse()
    {
        return new static('Empty response coming from the A1 API. Check URL path.');
    }

    public static function attemptingToSendEmptyMessage()
    {
        return new static('Trying to send an empty message to A1 API');
    }
}
