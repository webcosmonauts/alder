<?php

Route::group(['prefix' => 'alder'], function () {
    Route::name('alder.')->group(function () {
        foreach (\Webcosmonauts\Alder\Models\LeafType::all() as $type) {
            Route::resource($type->name, '\Webcosmonauts\Alder\Http\Controllers\BranchBREADController');
        }
    });
    
    // uploader
    Route::get('uploader', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index');
    Route::get('uploader-button', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index_button');
});
