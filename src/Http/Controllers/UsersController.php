<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Webcosmonauts\Alder\Facades\Alder;
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
     * Get [C]RUD view
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {

    }

    /**
     * Create and store new leaf from request
     *
     * @param Request $request
     */
    public function store(Request $request)
    {

    }

    public function edit(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function destroy(Request $request, $id)
    {

    }
}