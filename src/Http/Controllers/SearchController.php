<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafStatus;
use Webcosmonauts\Alder\Models\LeafType;

class SearchController extends BaseController
{
    public function search(Request $request) {
        $leaves = $request->search
            ? Leaf::whereTranslationLike('content', "%$request->search%")
                ->orWhereTranslationLike('title', "%$request->search%")
                ->whereIn('leaf_type_id', LeafType::whereIn('slug', ['posts', 'pages'])->pluck('id'))
                ->get()
            : [];
        
        $published = LeafStatus::whereSlug('published')->value('id');
        $leaves = $leaves->filter(function ($leaf) use ($published) {
            return $leaf->status_id == $published;
        });
        
        foreach ($leaves as $leaf)
            $leaf = Alder::populateWithLCMV($leaf, $leaf->leaf_type);
        
        return view('templates.nimoz.search')->with([
            'leaves' => $leaves,
        ]);
    }
}
