<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Leaf;

class SearchController extends BaseController
{
    public function search(Request $request) {
        $leaves = Leaf::whereTranslationLike('content', "%$request->search%")
            ->orWhereTranslationLike('title', "%$request->search%")->get();
        
        foreach ($leaves as $leaf)
            $leaf = Alder::populateWithLCMV($leaf, $leaf->leaf_type);
        
        return view('templates.nimoz.search')->with([
            'leaves' => $leaves,
        ]);
    }
}
