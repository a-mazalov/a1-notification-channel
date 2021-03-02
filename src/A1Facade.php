<?php

namespace A1\Channel;

use Illuminate\Support\Facades\Facade;

class A1Facade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return A1Client::class;
    }
}
