<?php

Route::get('setlocale/{locale}', function ($locale) {
    if (in_array($locale, config('translatable.locales'))) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
});

Route::group(['middleware' => 'locale-switcher'], function () {
    Route::get('alder/login', '\Webcosmonauts\Alder\Http\Controllers\Auth\LoginController@showLoginForm')->name('alder.login');
    Route::post('alder/login', '\Webcosmonauts\Alder\Http\Controllers\Auth\LoginController@login')->name('alder.login');
    Route::post('alder/logout', '\Webcosmonauts\Alder\Http\Controllers\Auth\LoginController@logout')->name('alder.logout');
    Route::get('alder/password/reset', '\Webcosmonauts\Alder\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm')->name('alder.password.request');
    Route::post('alder/password/email', '\Webcosmonauts\Alder\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail')->name('alder.password.email');
    
    //App\Http\Controllers
    Route::get('/register', '\Webcosmonauts\Alder\Http\Controllers\RegisterController@index')->name('register.index');
    Route::get('activation', '\Webcosmonauts\Alder\Http\Controllers\RegisterController@activation')->name('register.activation');
    Route::post('/register', '\Webcosmonauts\Alder\Http\Controllers\RegisterController@save')->name('register.save');
    Route::get('/verificated', '\Webcosmonauts\Alder\Http\Controllers\RegisterController@verificated')->name('register.verificated');
    
    Route::group(['prefix' => 'alder', 'middleware' => 'AlderGuard'], function () {
        Route::get('/', '\Webcosmonauts\Alder\Http\Controllers\DashboardController@index')->name('dashboard.index');

        Route::name('alder.')->group(function () {
            // LCM
            Route::resource('LCMs', '\Webcosmonauts\Alder\Http\Controllers\LCMController');

            // leaf types
            foreach (\Webcosmonauts\Alder\Models\LeafType::all() as $type) {
                Route::resource($type->slug, '\Webcosmonauts\Alder\Http\Controllers\BranchBREADController');
            }

            // roots
            Route::get('roots', '\Webcosmonauts\Alder\Http\Controllers\RootsController@index')->name('roots.index');
            Route::put('roots/update', '\Webcosmonauts\Alder\Http\Controllers\RootsController@update')->name('roots.update');

            // contact form params
            Route::resource('contact-forms', '\Webcosmonauts\Alder\Http\Controllers\ContactController');
            Route::post('contact-forms/save', '\Webcosmonauts\Alder\Http\Controllers\ContactController@save_form')->name('contact-forms.save_form');
            Route::get('contact-forms/read/{slug}', '\Webcosmonauts\Alder\Http\Controllers\ContactController@read')->name('contact-forms.read');
            Route::get('contact-forms/edit/{slug}', '\Webcosmonauts\Alder\Http\Controllers\ContactController@edit_mailer')->name('contact-forms.edit_mailer');
            Route::post('contact-forms/pars_mailer/{slug}', '\Webcosmonauts\Alder\Http\Controllers\ContactController@pars_mailer')->name('contact-forms.pars_mailer');

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

            //Register

            //Appearance
            Route::get('appearance', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController@appearance')->name('appearance.index');

            //Themes show
            Route::get('themes', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController@showThemes')->name('themes.index');
            //Select active theme
            Route::post('themes', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController@selectActiveTheme')->name('themes.update');

            //Menus
            Route::get('menus/create', '\Webcosmonauts\Alder\Http\Controllers\MenuController@menuCreate')->name('menus.create');
            Route::put('menus/{menu}', '\Webcosmonauts\Alder\Http\Controllers\MenuController@update')->name('menus.update');
            Route::get('menus/{menu}/edit', '\Webcosmonauts\Alder\Http\Controllers\MenuController@editMenu')->name('menus.edit');
            Route::get('menus/{menu}', '\Webcosmonauts\Alder\Http\Controllers\MenuController@show')->name('menus.show');
            Route::post('menus', '\Webcosmonauts\Alder\Http\Controllers\MenuController@store')->name('menus.store');
            Route::delete('menus/{menu}', '\Webcosmonauts\Alder\Http\Controllers\MenuController@deleteMenu')->name('menus.destroy');

            //Select active theme
            Route::get('theme-settings', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\ViewHierarchyController@index')->name('theme_settings.index');
            Route::post('theme-settings', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\ViewHierarchyController@setViewsHierarchy')->name('theme_settings.update');
            Route::post('theme-settings/save', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\ViewHierarchyController@saveRoots')->name('saveRoots');


            //Widgets builder
            Route::get('widgets', '\Webcosmonauts\Alder\Http\Controllers\WidgetsController@index')->name('widgets.index');
            Route::post('widgets', '\Webcosmonauts\Alder\Http\Controllers\WidgetsController@update')->name('widgets.update');

            // Uploader
            Route::get('media', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index');
            Route::get('media-button', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index_button');

            //Roles
            Route::get('roles', '\Webcosmonauts\Alder\Http\Controllers\AlderRolesController@index')->name('roles.index');
            Route::post('roles', '\Webcosmonauts\Alder\Http\Controllers\AlderRolesController@createBaseRoles')->name('roles.init_default_roles');
            Route::post('roles-add', '\Webcosmonauts\Alder\Http\Controllers\AlderRolesController@addNewRole')->name('roles.add_new');
            Route::post('roles-delete', '\Webcosmonauts\Alder\Http\Controllers\AlderRolesController@deleteRole')->name('roles.delete');

            /*//Capabilities
            Route::get('capabilities', '\Webcosmonauts\Alder\Http\Controllers\WidgetsController@index')->name('capabilities.index');
            Route::post('capabilities', '\Webcosmonauts\Alder\Http\Controllers\WidgetsController@update')->name('capabilities.update');*/
        });
    });
    
    Route::get('/', '\Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController@getIndexPage');
    
    // Search
    Route::get('search', '\Webcosmonauts\Alder\Http\Controllers\SearchController@search');
    Route::get('poszuk', '\Webcosmonauts\Alder\Http\Controllers\SearchController@search');
    
    Route::get("/{slug}", "\Webcosmonauts\Alder\Http\Controllers\LeafController@index");

    //Route::get("/{leaf_type}/{slug}","\Webcosmonauts\Alder\Http\Controllers\LeafController@leafTypeShow");
});
