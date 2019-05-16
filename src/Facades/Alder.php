<?php
namespace Webcosmonauts\Alder\Facades;

use Illuminate\Support\Facades\Facade;

class Alder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'alder';
    }
}
