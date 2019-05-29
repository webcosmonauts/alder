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
    Route::name('alder.')->group(function () {
        foreach (\Webcosmonauts\Alder\Models\LeafType::all() as $type) {
            Route::resource($type->name, 'Webcosmonauts\\Alder\\Http\\Controllers\\BranchBREADController');
        }
    });
});
