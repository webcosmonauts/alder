<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Webcosmonauts\Alder\Facades\Alder;

class FileManagerController
{
    public function index() {
        return view('alder::file-uploader.uploader')->with([
            'admin_menu_items' => $admin_menu_items = Alder::getMenuItems(),
        ]);
    }
    
    public function index_button() {
        return view('alder::file-uploader.uploader-button')->with([
            'admin_menu_items' => $admin_menu_items = Alder::getMenuItems(),
        ]);
    }
}