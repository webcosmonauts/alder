<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\AdminMenuItem;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafType;

class BranchBREADController extends BaseController
{
    /**
     * Get view of branch with leaves
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
     * Get read view for a leaf
     *
     * @param \Illuminate\Http\Request $request
     * @param string $param
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $param) {
        $branchType = $this->getBranchType($request);
    
        $model = Alder::getBranchModel($branchType);
    
        if (Schema::hasColumn($model->getTable(), 'slug')) {
            $leaf = $model::with('leaf_type')->where('slug', $param)->first();
        }
        else {
            $leaf = $model::with('leaf_type')->find($param);
        }
        
        $admin_menu_items = AdminMenuItem::with('children')->where('parent_id', null)->get();
        
        return view('alder::bread.read')->with([
            'leaf' => $leaf,
            'admin_menu_items' => $admin_menu_items,
        ]);
    }
    
    /**
     * Edit a leaf instance
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function edit() {
    
    }
    public function update() {
    
    }
    public function create() {
        return view('alder::bread.edit')->with([
            'edit' => false,
        ]);
    }
    public function destroy(Request $request, $id) {
    
    }
}