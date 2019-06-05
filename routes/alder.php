<?php

Route::group(['prefix' => 'alder'], function () {
    Route::name('alder.')->group(function () {
        // leaf types
        foreach (\Webcosmonauts\Alder\Models\LeafType::all() as $type) {
            Route::resource($type->name, '\Webcosmonauts\Alder\Http\Controllers\BranchBREADController');
        }
        
        // roots
        Route::get('roots', '\Webcosmonauts\Alder\Http\Controllers\RootsController@index')->name('roots.index');
    });
    
    // uploader
    Route::get('uploader', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index');
    Route::get('uploader-button', '\Webcosmonauts\Alder\Http\Controllers\FileManagerController@index_button');
});
