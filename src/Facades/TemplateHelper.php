<?php

namespace Webcosmonauts\Alder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Alder
 * @mixin \Webcosmonauts\Alder\Alder
 */

class TemplateHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'template_helper';
    }
}
