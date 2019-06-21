<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Webcosmonauts\Alder\Models\Leaf;

class SearchController extends BaseController
{
    public function search(Request $request) {
        $leaves = Leaf::whereTranslationLike('content', "%$request->search%")->get();
        
        return view('templates.nimoz.search')->with([
            'leaves' => $leaves,
        ]);
    }
}
