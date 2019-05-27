<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Webcosmonauts\Alder\Facades\Alder;

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
        
        $model = Alder::getBranchModel($branchType);
        
        $branch = $model::all();
        
        return view('alder::bread.browse')->with([
            'leaves' => $branch
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
            $leaf = $model::where('slug', $param)->first();
        }
        else {
            $leaf = $model::find($param);
        }
    
        return view('alder::bread.read')->with([
            'leaf' => $leaf
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
    
    }
    public function destroy() {
    
    }
}