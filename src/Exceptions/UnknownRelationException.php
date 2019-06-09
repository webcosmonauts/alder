<?php

namespace Webcosmonauts\Alder\Exceptions;

use Exception;

class UnknownRelationException extends Exception
{
    public function __construct($field) {
        parent::__construct(sprintf('Modifier field "%s" has unsupported relation', $field));
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
