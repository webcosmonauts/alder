<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\RootType;

class RootsController extends BaseController
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

        return view('alder::bread.roots.browse')->with([
            'root_types' => $root_types,
            'admin_menu_items' => $admin_menu_items,
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

    public function edit()
    {

    }

    public function update(Request $request)
    {

        foreach ($request->except(['_token']) as $key => $value) {
            Alder::setRootValue($key, $value);
            echo 'Updated' . $key . '<br>';

            return redirect()->back()->with(['success' => 'Settings is save']);
        }

    }

    public function destroy(Request $request, $id)
    {

    }
}
