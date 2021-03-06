<?php

namespace Webcosmonauts\Alder\Exceptions;

use Exception;

class NotNullableParameterWithoutValueException extends Exception
{
    public function __construct($param) {
        parent::__construct(sprintf('Not nullable parameter %s has no value in LCMV', $param));
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
