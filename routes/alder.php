<?php

Route::group(['prefix' => 'alder', 'middleware' => 'auth'], function () {
    Route::get('/', '\Webcosmonauts\Alder\Http\Controllers\DashboardController@index')->name('dashboard.index');

    Route::name('alder.')->group(function () {
        // LCM
        Route::resource('LCMs', '\Webcosmonauts\Alder\Http\Controllers\LCMController');

        // leaf types
        foreach (\Webcosmonauts\Alder\Models\LeafType::all() as $type) {
            Route::resource($type->slug, '\Webcosmonauts\Alder\Http\Controllers\BranchBREADController');
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
        Route::put('roots/update', '\Webcosmonauts\Alder\Http\Controllers\RootsController@update')->name('roots.update');

        // contact form params
        Route::resource('contact-forms', '\Webcosmonauts\Alder\Http\Controllers\ContactController');
        Route::post('contact-forms/save', '\Webcosmonauts\Alder\Http\Controllers\ContactController@save_form')->name('contact-forms.save_form');
        Route::get('contact-forms/read/{slug}', '\Webcosmonauts\Alder\Http\Controllers\ContactController@read')->name('contact-forms.read');

        //users
        Route::get('users', '\Webcosmonauts\Alder\Http\Controllers\UsersController@index')->name('users.index');
        Route::get('users/edit/{user}', '\Webcosmonauts\Alder\Http\Controllers\UsersController@edit')->name('users.edit');
        Route::put('users/edit/{user}', '\Webcosmonauts\Alder\Http\Controllers\UsersController@update')->name('users.update');
        Route::get('users/read/{user}', '\Webcosmonauts\Alder\Http\Controllers\UsersController@show')->name('users.show');
        Route::get('users/destroy/{user}', '\Webcosmonauts\Alder\Http\Controllers\UsersController@destroy')->name('users.destroy');
        Route::get('users/create', '\Webcosmonauts\Alder\Http\Controllers\UsersController@create')->name('users.create');
        Route::post('users/store', '\Webcosmonauts\Alder\Http\Controllers\UsersController@store')->name('users.store');

        //profile
        Route::get('profile', '\Webcosmonauts\Alder\Http\Controllers\ProfileController@index')->name('profile.index');

        //Appearance
        Route::get('appearance', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController@appearance')->name('appearance.index');
        //Themes show

        Route::get('themes', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController@showThemes')->name('themes.index');
        //Select active theme
        Route::post('themes', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController@selectActiveTheme')->name('themes.update');
    });

    // uploader
    Route::get('media', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index');
    Route::get('media-button', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index_button');
});

Route::get("/{slug}","\Webcosmonauts\Alder\Http\Controllers\LeafController@index");
