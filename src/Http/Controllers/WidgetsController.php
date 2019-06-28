<?php


namespace Webcosmonauts\Alder\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\Root;

class WidgetsController extends Controller
{
    public function index(){
        $check_permission = Alder::checkPermission('update themes');
        if ($check_permission !== true)
            return $check_permission;
        
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        //Get active theme value
        $active_theme = Alder::getRootValue('active-theme');

        $widgets = Root::where("slug",'like','%widget%')->get();

        /* Return view with prefilled data */
        return view('alder::bread.widgets.browse')->with([
            'active_theme' => $active_theme,
            'admin_menu_items' => $admin_menu_items,
            'widgets'=>$widgets
        ]);
    }
    public function update(Request $request)
    {
        $check_permission = Alder::checkPermission('update themes');
        if ($check_permission !== true)
            return $check_permission;
        foreach ($request->except(['_token']) as $key => $value) {
            Alder::setRootValue($key, $value);
            echo 'Updated' . $key . '<br>';

            return redirect()->back()->with(['success' => 'Settings is save']);
        }

    }
}
