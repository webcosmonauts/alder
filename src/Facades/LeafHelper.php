<?php
namespace Webcosmonauts\Alder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Alder
 * @mixin \Webcosmonauts\Alder\Alder
 */
class LeafHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'leaf_helper';
    }
}
