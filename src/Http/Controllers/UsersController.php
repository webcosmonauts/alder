<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\RootType;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\User;
use Symfony\Component\HttpFoundation\File;

class UsersController extends BaseController
{
    /**
     * Get view of users
     *
     * @return View
     */
    public function index()
    {
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        /* Get leaf type with custom modifiers */
        $root_types = RootType::with('roots')->get();

        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        $users = User::all();
        $roles = Role::all();

        return view('alder::bread.users.browse')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'users' => $users,
            'roles'=>$roles
        ]);
    }



    /**
     *
     * Get view of one user
     * @param Request $request, $id user
     * @return View
     *
     */
    public function show(Request $request, $id) {
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;

        $user = User::where('id',$id)->get();
        $user = $user[0];

        $img = asset('img/users/'.$user->avatar);

        return view('alder::bread.users.read')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'user' => $user,
            'img' => $img
        ]);
    }

    /**
     * Create new user from request
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        $user = 0;
        return view('alder::bread.users.edit')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'user' => $user
        ]);
    }

    /**
     * Create and store new leaf from request
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        $edit = false;
        $id = 185448;
        return $this->editUser($edit, $request, $id);
    }

    public function edit(Request $request, $id)
    {
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        $user = User::where('id',$id)->get();
        $user = $user[0];
        $roles = Role::all();

        return view('alder::bread.users.edit')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'user' => $user,
            'roles'=>$roles
        ]);
    }

    public function update(Request $request, $id)
    {
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        $edit = true;
        return $this->editUser($edit, $request, $id);
    }

    public function destroy(Request $request, int $id) {
        $check_permission = Alder::checkPermission('edit users');
        if ($check_permission !== true)
            return $check_permission;
        return
            User::where('id',$id)->delete()

                ? Alder::returnResponse(
                $request->ajax(),
                __('alder::messages.delete_successfully'),
                true,
                'success'
            )
                : Alder::returnResponse(
                $request->ajax(),
                __('alder::messages.processing_error'),
                false,
                'danger'
            );
    }

    private function editUser(bool $edit, Request $request, $id) {
        return DB::transaction(function () use ($edit, $request, $id) {
            try {
                $User = $edit ? User::where('id',$id)->get()->first() : new User();

                $User->name = $request->name;
                $User->surname = $request->surname;
                $User->email = $request->email;
                if ($request->password)
                    $User->password = bcrypt($request->password);
                $User->is_active = $request->is_active != 'on' ? 0 : 1;
                $User->LCM_id = $request->LCM_id;
                $User->LCMV_id = $request->LCMV_id;

                if ($request->userfile) {
                    $type_file = $request->userfile->getClientOriginalExtension();
                    $size_file = $request->userfile->getSize();
                    if ($type_file == 'jpg' | $type_file == 'jpeg' | $type_file == 'png') {
                        if ($size_file < 2000000) {
                            $file_name = ($request->userfile->getClientOriginalName());
                            $file_name_db = '_'. date('mdYHis'). '_' . $request->name . '_' . $file_name;
                            $User->avatar = $request->file('userfile')->storeAs('users', $file_name_db, 'public');
                        } else {
                            return redirect("alder/users/edit/$id")->with(['error_image'=> __('alder::messages.error_avatar')]);
                        }
                    }else {
                        return redirect("alder/users/edit/$id")->with(['error_image'=> __('alder::messages.error_avatar')]);
                    }
                }

                if (!$edit)
                    $User->created_at = date("Y-m-d H:i:s");
                $User->updated_at = date("Y-m-d H:i:s");
                if(empty($request->roles) || (count($request->roles) == 1 && in_array("subscriber", $request->roles))){
                    $User->type = 'user';
                } else
                    $User->type = 'administrator';
                $User->save();
                $User->syncRoles($request->roles);

                if ($User->id == Auth::user()->id) {
                    return Alder::returnRedirect(
                        $request->ajax(),
                        __('alder::generic.successfully_'
                            . ($edit ? 'updated' : 'created')) . " $User->name",
                        '/alder',
                        true,
                        'success'
                    );
                }

                return Alder::returnRedirect(
                    $request->ajax(),
                    __('alder::generic.successfully_'
                        . ($edit ? 'updated' : 'created')) . " $User->name",
                    route("alder.users.index"),
                    true,
                    'success'
                );
            } catch (Exception $e) {
                DB::rollBack();
                return Alder::returnResponse(
                    $request->ajax(),
                    __('alder::messages.processing_error'),
                    false,
                    'danger',
                    $e->getMessage()
                );
            }
        });
    }

}
