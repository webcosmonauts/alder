<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
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
        /* Get leaf type with custom modifiers */
        $root_types = RootType::with('roots')->get();

        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        $users = User::all();


        return view('alder::bread.users.browse')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'users' => $users
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
        $edit = false;
        $id = 185448;
        return $this->editUser($edit, $request, $id);
    }

    public function edit(Request $request, $id)
    {

        $user = User::where('id',$id)->get();
        $user = $user[0];

        return view('alder::bread.users.edit')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $edit = true;
        return $this->editUser($edit, $request, $id);
    }

    public function destroy(Request $request, $id) {

        return
            User::where('id', $id)->delete()
                ? Alder::returnResponse(
                $request->ajax(),
                __('user::messages.processing_error'), // todo deleted successfully
                true,
                'success'
            )
                : Alder::returnResponse(
                $request->ajax(),
                __('user::messages.processing_error'),
                false,
                'danger'
            );
    }

    private function editUser(bool $edit, Request $request, $id) {
        return DB::transaction(function () use ($edit, $request, $id) {
            try {

                $file_div = '';
                $file_name_db = '';
                if ($request->userfile) {
                    $file_name = ($request->userfile->getClientOriginalName());
                    $file_div = public_path() . '\img\users\_'. date('mdYHis'). '_' . $request->name . '_' . $file_name;
                    $file_name_db = '_'. date('mdYHis'). '_' . $request->name . '_' . $file_name;


                    if (move_uploaded_file($request->userfile, $file_div)) {
                        echo 'good';

                    } else {
                        echo 'error';
                    }
                }


                $User = $edit ? User::where('id',$id)->get()->first() : new User();

                $User->name = $request->name;
                $User->surname = $request->surname;
                $User->email = $request->email;
                if ($request->password)
                    $User->password = bcrypt($request->password);
                $User->is_active = $edit ? $request->is_active : 0;
                $User->LCM_id = $request->LCM_id;
                $User->LCMV_id = $request->LCMV_id;
                if ($file_name_db)
                    $User->avatar = $file_name_db;

                if (!$edit)
                    $User->created_at = date("Y-m-d H:i:s");
                $User->updated_at = date("Y-m-d H:i:s");

                $User->save();

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