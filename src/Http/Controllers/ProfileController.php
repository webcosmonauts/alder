<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    /**
     * Get [B]READ view of roots
     *
     * @throws AssigningNullToNotNullableException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownConditionOperatorException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownConditionParameterException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownRelationException
     *
     * @return View
     */
    public function index()
    {
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        $user = Auth::user();
        $roles = Role::all();

        return view('alder::bread.profile.profile')->with([
            'admin_menu_items' => $admin_menu_items,
            'user' => $user,
            'roles' => $roles,
        ]);
    }
}