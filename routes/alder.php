<?php

Route::group(['prefix' => 'alder', 'middleware' => 'auth'], function () {
    Route::name('alder.')->group(function () {
        // leaf types
        foreach (\Webcosmonauts\Alder\Models\LeafType::all() as $type) {
            Route::resource($type->name, '\Webcosmonauts\Alder\Http\Controllers\BranchBREADController');
        }

        // Authentication Routes
        //Route::get('login', 'Auth\AlderLoginController@showLoginFormUsers')->name('login');
        //Route::post('users', 'Auth\AlderLoginController@checklogin');
        //Route::post('logout', 'Auth\AlderLoginController@logout')->name('logout');
// Registration Routes
        //Route::get('users/registration', 'Auth\RegisterController@showRegistrationFormUsers')->name('register');
        //Route::post('users/registration', 'Auth\RegisterController@register');

        // roots
        Route::get('roots', '\Webcosmonauts\Alder\Http\Controllers\RootsController@index')->name('roots.index');
        Route::post('roots/update', '\Webcosmonauts\Alder\Http\Controllers\RootsController@update')->name('roots.update');


        // contact form
        Route::get('contact-form', 'ContactController@create')->name('contact-form');
        Route::post('contact-form', 'ContactController@store')->name('contact-form');





    });

    // uploader
    Route::get('uploader', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index');
    Route::get('uploader-button', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index_button');


});
