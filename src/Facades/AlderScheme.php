<?php
namespace Webcosmonauts\Alder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Alder
 * @mixin \Webcosmonauts\Alder\Structure\AlderScheme
 */
class AlderScheme extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'alderscheme';
    }
}
