<?php

namespace Webcosmonauts\Alder\Exceptions;

use Exception;

class ModifierTypeNotSupportedException extends Exception
{
    public function __construct($type) {
        parent::__construct(sprintf('Modifier type %s is not supported', $type));
    }
    
    /*
     * Report the exception.
     *
     * @return void
    public function report()
    {
        //
    }
    
    /*
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
    public function render($request)
    {
        return response(...);
    }
    */
}
