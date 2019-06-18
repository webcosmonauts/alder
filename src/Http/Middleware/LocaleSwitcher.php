<?php
namespace Webcosmonauts\Alder\Http\Middleware;

use Illuminate\Support\Facades\App;

class LocaleSwitcher
{
    public function handle($request, \Closure $next) {
        App::setLocale(session('locale'));
        if (session()->has('locale') && in_array(session('locale'), config('translatable.locales'))) {
            App::setLocale(session('locale'));
        }
        return $next($request);
    }
}