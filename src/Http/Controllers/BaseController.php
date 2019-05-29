<?php
    
namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as IlluminateController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Str;

class BaseController extends IlluminateController 
{
    use ValidatesRequests, AuthorizesRequests;
    
    /**
     * Get branch type (e.g. 'post') based on route name (e.g. 'alder.post.index')
     *
     * @param Request $request
     *
     * @return string
     */
    public function getBranchType(Request $request) {
        return explode('.', $request->route()->getName())[1];
    }
}