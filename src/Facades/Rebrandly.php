<?php

namespace Spotawheel\Rebrandly\Facades;

use Illuminate\Support\Facades\Facade;


class Rebrandly extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rebrandly';
    }
}