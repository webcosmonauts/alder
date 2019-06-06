<?php
namespace Webcosmonauts\Alder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Alder
 * @mixin \Webcosmonauts\Alder\Alder
 */
class Alder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'alder';
    }
}
