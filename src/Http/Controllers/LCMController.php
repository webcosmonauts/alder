<?php
namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafCustomModifier;

class LCMController extends BaseController
{
    public function index(Request $request) {
        $LCMs = LeafCustomModifier::all();
        
        $admin_menu_items = Alder::getMenuItems();
        
        return view('alder::bread.LCMs.browse')->with(compact(
            'LCMs', 'admin_menu_items'
        ));
    }

    public function show(Request $request) {
    
    }

    public function create(Request $request) {
    
    }

    public function store(Request $request) {
    
    }

    public function edit(Request $request) {
    
    }

    public function update(Request $request) {
    
    }

    public function destroy(Request $request) {
    
    }
}
