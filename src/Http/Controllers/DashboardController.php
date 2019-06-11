<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\View\View;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\User;

class DashboardController extends BaseController
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
        
        $post_id = LeafType::where('slug', 'posts')->value('id');
        $posts = Leaf::where('leaf_type_id', $post_id)->count();
        
        $lastpost = Leaf::where('leaf_type_id', $post_id)->orderBy('updated_at', 'desc')->first();
        
        $page_id = LeafType::where('slug', 'pages')->value('id');
        $pages = Leaf::where('leaf_type_id', $page_id)->count();
        
        $lastpage = Leaf::where('leaf_type_id', $page_id)->orderBy('updated_at', 'desc')->first();
        
        $users = User::where('is_active', 1)->count();
        $users_all = User::all()->count();
        
        return view('alder::dashboard')->with([
            'admin_menu_items' => $admin_menu_items,
            'posts' => $posts,
            'pages' => $pages,
            'users' => $users,
            'users_all' => $users_all,
            'lastpage' => $lastpage,
            'lastpost' => $lastpost
        ]);
    }
}