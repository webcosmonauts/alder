<?php


namespace Webcosmonauts\Alder\Http\Controllers;


class FileManagerController
{
    public function index() {
        return view('alder::layouts.uploader');
    }
}