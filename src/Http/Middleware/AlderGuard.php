<?php
namespace Webcosmonauts\Alder\Http\Middleware;

use Illuminate\Support\Facades\Auth;

class AlderGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        if( Auth::user() && Auth::user()->isAdmin() ) {

            return $next($request);
        }
        return redirect('/');
    }

}
