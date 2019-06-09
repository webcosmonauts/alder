<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\RootType;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\User;

class DashboardController extends BaseController
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

        $sql = LeafType::where('name','posts' )->value('id');
        $posts = Leaf::where('leaf_type_id', $sql)->count();
        $lastpost = Leaf::where('leaf_type_id', $sql)->orderBy('updated_at','desc')->first();

        $sql = LeafType::where('name','pages' )->value('id');
        $pages = Leaf::where('leaf_type_id', $sql)->count();
        $lastpage = Leaf::where('leaf_type_id', $sql)->orderBy('updated_at','desc')->first();



        $users = User::where('is_active', 1)->count();
        $users_all = User::all()->count();


//        $lastpage = Leaf::where('updated_at', );




        return view('alder::dashboard')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'posts' => $posts,
            'pages' => $pages,
            'users' => $users,
            'users_all' => $users_all,
            'lastpage' => $lastpage,
            'lastpost' => $lastpost
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