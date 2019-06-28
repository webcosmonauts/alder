<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Webcosmonauts\Alder\Facades\Alder;

class FileManagerController
{
    public function index() {
        $check_permission = Alder::checkPermission('upload files');
        if ($check_permission !== true)
            return $check_permission;
        return view('alder::bread.file-uploader.uploader')->with([
            'admin_menu_items' => $admin_menu_items = Alder::getMenuItems(),
        ]);
    }
    
    public function index_button() {
        $check_permission = Alder::checkPermission('upload files');
        if ($check_permission !== true)
            return $check_permission;
        return view('alder::bread.file-uploader.uploader-button')->with([
            'admin_menu_items' => $admin_menu_items = Alder::getMenuItems(),
        ]);
    }
}