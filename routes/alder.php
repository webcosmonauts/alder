<?php

Route::group(['prefix' => 'alder'], function () {
    Route::name('alder.')->group(function () {
        foreach (\Webcosmonauts\Alder\Models\LeafType::all() as $type) {
            Route::resource($type->name, '\Webcosmonauts\Alder\Http\Controllers\BranchBREADController');
        }
    });
});

Route::get('uploader', function () {
    return view('alder::uploader');
});
