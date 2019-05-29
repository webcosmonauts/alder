<?php

namespace Webcosmonauts\Alder\Exceptions;

use Exception;

class AssigningNullToNotNullableException extends Exception
{
    public function __construct() {
        parent::__construct('Modifier field %s is not nullable and does not have default value');
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
