<?php

namespace A1\Channel\Exceptions;

class ExceptionApi extends \Exception
{
    public static function serviceRespondedWithAnError($response)
    {
        return new static(json_encode($response, JSON_UNESCAPED_UNICODE));
    }
}
