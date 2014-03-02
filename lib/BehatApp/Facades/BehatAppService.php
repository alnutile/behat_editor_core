<?php namespace BehatApp\Facades;

use Illuminate\Support\Facades\Facade;

class BehatAppService extends Facade {


    public static function getFacadeAccessor()
    {
        return 'BehatTest';
    }

}