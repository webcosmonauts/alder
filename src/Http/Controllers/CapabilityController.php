<?php


namespace Webcosmonauts\Alder\Http\Controllers;


use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Permissions;
use Webcosmonauts\Alder\Models\Roles;

class CapabilityController
{

    /**
     * @var array
     */
    protected $capabilities = [
        'subscriber_capabilities'=>[
            'read'
        ],
        'contributor_capabilities' => [
            'delete posts',
            'edit posts',
        ],
        'author_capabilities' => [
            'delete published posts',
            'publish posts',
            'upload files',
            'edit published posts',
        ],
        'editor_capabilities'=>[
            'unfiltered html',
            'read private pages',
            'edit private pages',
            'delete private pages',
            'read private posts',
            'edit private posts',
            'delete private posts',
            'delete others posts',
            'delete published pages',
            'delete others pages',
            'delete pages',
            'publish pages',
            'edit published pages',
            'edit others pages',
            'edit pages',
            'edit others posts',
            'manage links',
            'manage categories',
            'moderate comments'
        ],
        'administrator_capabilities'=>[
            'delete site',
            'customize',
            'edit dashboard',
            'update themes',
            'update plugins',
            'update core',
            'switch themes',
            'remove users',
            'promote users',
            'manage options',
            'list users',
            'install themes',
            'install plugins',

            'import',
            'export',
            'edit users',
            'edit themes',
            'edit theme options',
            'edit plugins',
            'edit files',
            'delete users',
            'delete themes',
            'delete plugins',
            'create users',
            'activate plugins'
        ],
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownConditionOperatorException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownConditionParameterException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownRelationException
     */
    public function index(){
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        //Get active theme value
        $active_theme = Alder::getRootValue('active-theme');

        //Roles
        $roles = Roles::all();
        $permissions = Permissions::all();

        /* Return view with prefilled data */
        return view('alder::bread.permissions.browse')->with([
            'active_theme' => $active_theme,
            'admin_menu_items' => $admin_menu_items,
            'roles'=>$roles,
            'permissions'=>$permissions,
        ]);
    }

    public function update(){

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initDefaultCapabilities(){
        $existing_permissions = Permissions::all()->pluck('name')->toArray();
        //dd($existing_permissions);

        $capabilities = $this->capabilities;

        foreach ($capabilities as $single_role_capabilities){
            foreach ($single_role_capabilities as $single_capability){
                if(!in_array($single_capability,$existing_permissions)):
                    $permission = Permission::create(['name' => $single_capability, 'guard_name'=>'AlderGuard']);
                endif;
            }

        }
        return redirect()->back()->with('Default capabilities init');
    }

    public function assignDefaultCapabilitiesToRoles(){
        $roles_raw = Role::all('name')->toArray();
        
        dd($roles_raw);
        return redirect()->back()->with('Default capabilities for roles init');
    }


}
