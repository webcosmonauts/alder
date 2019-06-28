<?php


namespace Webcosmonauts\Alder\Http\Controllers;


use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Webcosmonauts\Alder\Facades\Alder;

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
            'browse leaves',
            'edit leaves',
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
            'moderate comments',
            'publish reports'
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
    public function index(Request $request){
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        $role = null;
        $selected_role = null;
        if(isset($request->role)){
            $role=(int)$request->role;
            $selected_role = Role::where('id',$role)->firstOrFail();
        }

        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        //Get active theme value
        $active_theme = Alder::getRootValue('active-theme');

        //Roles
        $roles = Role::all();
        $permissions = Permission::all();

        /* Return view with prefilled data */
        return view('alder::bread.permissions.browse')->with([
            'active_theme' => $active_theme,
            'admin_menu_items' => $admin_menu_items,
            'roles'=>$roles,
            'permissions'=>$permissions,
            'role_editor_enabler'=>$role,
            'selected_role'=>$selected_role
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function deleteCapability(Request $request){
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        return Permission::where('id',$request->id)->delete() ? Alder::returnResponse(
            false,
            __('alder::permissions.capability_deleted'),
            true,
            'success'
        ) : Alder::returnResponse(
            false,
            __('alder::permissions.something_went_wrong'),
            true,
            'danger'
        );
    }

    public function update(){

    }



    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function initDefaultCapabilitiesAndRedirect(){
        $existing_permissions = Permission::all()->pluck('name')->toArray();
        //dd($existing_permissions);

        $capabilities = $this->capabilities;

        foreach ($capabilities as $single_role_capabilities){

            foreach ($single_role_capabilities as $single_capability) {

                //dd($single_role_capabilities, $single_capability, $capabilities);
                if (!in_array($single_capability, $existing_permissions)){
                    $temp = new Permission();
                    $temp->name = $single_capability;
                    $temp->guard_name = 'AlderGuard';
                    $temp->save();
                    //$permission = Permission::new(['name' => $single_capability, 'guard_name'=>'AlderGuard']);
                }
            }

        }
        return Alder::returnResponse(
            false,
            __('alder::permissions.default_capabilities_init'),
            true,
            'success'
        );
    }

    /**
     *
     */
    public function initDefaultCapabilities(){
        $existing_permissions = Permission::all()->pluck('name')->toArray();
        //dd($existing_permissions);

        $capabilities = $this->capabilities;

        foreach ($capabilities as $single_role_capabilities){

            foreach ($single_role_capabilities as $single_capability) {

                //dd($single_role_capabilities, $single_capability, $capabilities);
                if (!in_array($single_capability, $existing_permissions)){
                    $temp = new Permission();
                    $temp->name = $single_capability;
                    $temp->guard_name = 'AlderGuard';
                    $temp->save();

                }
            }

        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function assignDefaultCapabilitiesToRoles(){
        $this->initDefaultCapabilities();
        $roles_raw = Role::all();
        /*foreach ($roles_raw as $role){
          $role->syncPermissions($this->capabilities[$role->name.'_capabilities']);
        }*/
        //Contributor capabilities
        $subscriber_roles = $roles_raw->where('name','subscriber')->first();
        $subscriber_roles->syncPermissions([
            $this->capabilities['subscriber_capabilities'],
        ]);

        //Contributor capabilities
        $contributor_roles = $roles_raw->where('name','contributor')->first();
        $contributor_roles->syncPermissions([
            $this->capabilities['subscriber_capabilities'],
            $this->capabilities['contributor_capabilities']
        ]);

        //Author capabilities
        $author_roles = $roles_raw->where('name','author')->first();
        $author_roles->syncPermissions([
            $this->capabilities['subscriber_capabilities'],
            $this->capabilities['contributor_capabilities'],
            $this->capabilities['author_capabilities'],
        ]);

        //Editor capabilities
        $editor_roles = $roles_raw->where('name','editor')->first();
        $editor_roles->syncPermissions([
            $this->capabilities['subscriber_capabilities'],
            $this->capabilities['contributor_capabilities'],
            $this->capabilities['author_capabilities'],
            $this->capabilities['editor_capabilities'],
        ]);

        //Administrator capabilities
        $administrator_roles = $roles_raw->where('name','administrator')->first();
        $administrator_roles->syncPermissions([
            $this->capabilities['subscriber_capabilities'],
            $this->capabilities['contributor_capabilities'],
            $this->capabilities['author_capabilities'],
            $this->capabilities['editor_capabilities'],
            $this->capabilities['administrator_capabilities'],
        ]);

        //

        return Alder::returnResponse(
            false,
            __('alder::permissions.default_capabilities_for_roles_init'),
            true,
            'success'
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function addNewCapability(Request $request){
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        $existing_permissions = Permission::all()->pluck('name')->toArray();

        if(isset($request->new_capability)){
            $new_capability = $request->new_capability;
            if(!in_array($new_capability, $existing_permissions)){
                $temp = new Permission();
                $temp->name = $new_capability;
                $temp->guard_name = 'AlderGuard';
                $temp->save();
                return Alder::returnResponse(
                    $request->ajax(),
                    __('alder::permissions.new_capability_created'),
                    true,
                    'success'
                );

            }
            else{
                return Alder::returnResponse(
                    $request->ajax(),
                    __('alder::permissions.capability_already_exist'),
                    true,
                    'danger'
                );
            }

        }
        else{
            return Alder::returnResponse(
                $request->ajax(),
                __('alder::permissions.no_capability_specified'),
                true,
                'danger'
            );

        }
    }

    public function updateRolesCapabilities(Request $request){
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        if(isset($request->selected_role)){
            $role_raw = Role::where('id',$request->selected_role)->firstOrFail();
            if(isset($request->permissions)){
                //dd($request->permissions);
                $role_raw->syncPermissions($request->permissions);
                return Alder::returnResponse(
                    $request->ajax(),
                    __('alder::permissions.saved'),
                    true,
                    'success'
                );
            }
            else{
                return Alder::returnResponse(
                    $request->ajax(),
                    __('alder::permissions.saved'),
                    true,
                    'success'
                );
            }
        }
        else{
            return Alder::returnResponse(
                $request->ajax(),
                __('alder::permissions.no_role_specified'),
                true,
                'danger'
            );
        }
    }
}
