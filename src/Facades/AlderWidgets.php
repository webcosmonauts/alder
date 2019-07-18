<?php
namespace Webcosmonauts\Alder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Alder
 * @mixin \Webcosmonauts\Alder\Facades\Classes\Widgets
 */
class AlderWidgets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'alder_widgets';
    }
}
