<?php

/*
|--------------------------------------------------------------------------
| Voyager Routes
|--------------------------------------------------------------------------
|
| This file is where you may override any of the routes that are included
| with Voyager.
|
*/
// Route::group(['namespace' => 'Webcosmonauts\Alder\Http\Controllers'], function() {

Route::group(['prefix' => 'alder'], function () {
    foreach (Alder::getBranchTypes(true) as $type) {
        $type = Str::plural(Str::kebab($type));
        Route::get("$type", "Webcosmonauts\\Alder\\Http\\Controllers\\BranchBREADController@index")
            ->name("alder.$type.index");
        Route::get("$type/{slug}", "Webcosmonauts\\Alder\\Http\\Controllers\\BranchBREADController@show")
            ->name("alder.$type.show");
    }
});
