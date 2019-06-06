<?php

Route::group(['prefix' => 'alder'], function () {
    Route::name('alder.')->group(function () {
        // leaf types
        foreach (\Webcosmonauts\Alder\Models\LeafType::all() as $type) {
            Route::resource($type->name, '\Webcosmonauts\Alder\Http\Controllers\BranchBREADController');
        }

        // roots
        Route::get('roots', '\Webcosmonauts\Alder\Http\Controllers\RootsController@index')->name('roots.index');
    
        // contact form
        Route::get('contact-form', 'ContactController@create')->name('contact-form');
        Route::post('contact-form', 'ContactController@store')->name('contact-form');
    });

    // uploader
    Route::get('uploader', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index');
    Route::get('uploader-button', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index_button');
});
