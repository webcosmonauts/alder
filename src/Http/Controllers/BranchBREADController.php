<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
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
        $branchType = ucfirst(Str::camel(Str::singular($this->getBranchType($request))));
        
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
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request, string $slug) {
    
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