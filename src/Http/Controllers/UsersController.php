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

class UsersController extends BaseController
{
    /**
     * Get [B]READ view of roots
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


        return view('alder::users.browse')->with([
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

        return view('alder::users.read')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'user' => $user
        ]);
    }

    /**
     * Get [C]RUD view
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        $user = 0;
        return view('alder::users.edit')->with([
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

        echo 'store';
    }

    public function edit(Request $request, $id)
    {

        $user = User::where('id',$id)->get();
        $user = $user[0];

        return view('alder::users.edit')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        dd($request->name);
        return $this->editUser($request, $id);
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

    private function editUser(Request $request, $id) {
        return DB::transaction(function () use ($request, $id) {
            try {
                $leaf = $id ? $edit_leaf : new Leaf();
                $LCMV = $edit ? $leaf->LCMV : new LeafCustomModifierValue();

                $values = [];
                foreach ($params->fields as $field_name => $modifiers) {
                    if (isset($request->$field_name) && !empty($request->$field_name))
                        $values[$field_name] = $request->$field_name;
                    else {
                        if (isset($modifiers->default))
                            $values[$field_name] = $modifiers->default;
                        else if (isset($modifiers->nullable) && $modifiers->nullable)
                            $values[$field_name] = null;
                        else
                            throw new AssigningNullToNotNullableException($field_name);
                    }
                }
                $LCMV->values = $values;
                $LCMV->save();

                $leaf->title = $request->title;
                $leaf->slug = $request->slug;
                $leaf->content = $request->get('content');
                $leaf->user_id = $request->user_id;
                $leaf->status_id = 5;
                $leaf->leaf_type_id = $leaf_type->id;
                $leaf->LCMV_id = $LCMV->id;
                $leaf->save();

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