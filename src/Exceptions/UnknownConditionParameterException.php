<?php

namespace Webcosmonauts\Alder\Exceptions;

use Exception;

class UnknownConditionParameterException extends Exception
{
    public function __construct() {
        parent::__construct('Unknown condition type');
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
