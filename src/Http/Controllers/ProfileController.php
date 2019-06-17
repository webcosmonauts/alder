<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\View\View;
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
     * @return View
     */
    public function index()
    {
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        $id = Auth::user()->id;

        $user = User::where('id', $id)->first();

        $img = asset('img/users/'.$user->avatar);


        return view('alder::bread.profile.profile')->with([
            'admin_menu_items' => $admin_menu_items,
            'user' => $user,
            'img' => $img
        ]);
    }
}