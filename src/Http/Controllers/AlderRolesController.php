<?php

namespace Webcosmonauts\Alder\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Roles;
use Webcosmonauts\Alder\Models\Root;

class AlderRolesController extends Controller
{
    public function index(){
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        //Get active theme value
        $active_theme = Alder::getRootValue('active-theme');

        //Roles
        $roles = Roles::all();

        /* Return view with prefilled data */
        return view('alder::bread.roles.browse')->with([
            'active_theme' => $active_theme,
            'admin_menu_items' => $admin_menu_items,
            'roles'=>$roles
        ]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createBaseRoles(){
        $rolesArray = [
            'subscriber',
            'contributor',
            'author',
            'editor',
            'administrator',
        ];
        foreach ($rolesArray as $single_role):
            $existing_role = Role::findOrCreate($single_role,'AlderGuard');
        endforeach;
        return redirect()->back();
    }

    public function addNewRole(Request $request){
        if(!empty($request->name)){
            $new_role = Role::findOrCreate($request->name,'AlderGuard');
            return redirect()->back()->with('Role added');
        }

    }

    public function deleteRole(Request $request){
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        return Role::where('id',$request->id)->delete() ? redirect()->back() : redirect()->back();
    }
}
