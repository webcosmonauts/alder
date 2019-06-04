<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\AdminMenuItem;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Models\LeafType;

class BranchBREADController extends BaseController
{
    /**
     * Get [B]READ view of branch with leaves
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) {
        $branchType = $this->getBranchType($request);
    
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
        
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLeafTypeLCMs($leaf_type);
        
        /* Get branch instance and all its leaves */
        $branch = Leaf::with('LCMV')->where('leaf_type_id', $leaf_type->id)->get();
        
        /* Populate models with values from LCMV */
        foreach ($branch as &$leaf)
            Alder::populateWithLCMV($leaf, $leaf_type, $params);
        
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();
        
        return view('alder::bread.browse')->with([
            'leaves' => $branch,
            'leaf_type' => $leaf_type,
            'admin_menu_items' => $admin_menu_items,
            'params' => $params,
        ]);
    }
    
    /**
     * Get C[R]UD view for a leaf
     *
     * @param \Illuminate\Http\Request $request
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $slug) {
        $branchType = $this->getBranchType($request);
    
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
    
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLeafTypeLCMs($leaf_type);
    
        /* Get leaf */
        $leaf = Leaf::with('LCMV')->where('slug', $slug)->first();
    
        /* Populate model with values from LCMV */
        Alder::populateWithLCMV($leaf, $leaf_type, $params);
    
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();
        
        return view('alder::bread.read')->with([
            'leaf' => $leaf,
            'leaf_type' => $leaf_type,
            'params' => $params,
            'admin_menu_items' => $admin_menu_items,
        ]);
    }
    
    /**
     * Get [C]RUD view
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request) {
        $branchType = $this->getBranchType($request);
        
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
        
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLeafTypeLCMs($leaf_type);
        
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();
        
        return view('alder::bread.edit')->with([
            'edit' => false,
            'leaf_type' => $leaf_type,
            'admin_menu_items' => $admin_menu_items,
            'params' => $params,
        ]);
    }
    
    /**
     * Create and store new leaf from request
     */
    public function store(Request $request) {
        $branchType = $this->getBranchType($request);
    
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
    
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLeafTypeLCMs($leaf_type);
        
        $staticFields = ['title', 'slug', 'content', 'user_id'];
        
        DB::transaction(function () use ($staticFields) {
            $LCMV = new LeafCustomModifierValue();
            $values = [];
            foreach ($request->except($staticFields) as $key => $value) {
                $values->$key = $value;
            }
            $LCMV->values = $values;
            $LCMV->save();
    
            $leaf = new Leaf();
            $leaf->title = $request->title;
            $leaf->slug = $request->slug;
            $leaf->content = $request->get('content');
            $leaf->user_id = $request->user_id;
            $leaf->status_id = 5;
            $leaf->leaf_type_id = $leaf_type->id;
            $leaf->LCMV_id = $LCMV->id;
            $leaf->save();
        });
    }
    
    /**
     * Edit a leaf instance
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit() {
    }
    public function update() {
    
    }
    public function destroy(Request $request, $id) {
    
    }
}