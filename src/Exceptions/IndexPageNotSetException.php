<?php

namespace Webcosmonauts\Alder\Exceptions;

use Exception;

class IndexPageNotSetException extends Exception
{
    public function __construct() {
        parent::__construct('Index page is not set in roots');
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
