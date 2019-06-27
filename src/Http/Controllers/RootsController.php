<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Exceptions\UnknownRelationException;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafStatus;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\RootType;

class RootsController extends BaseController
{
    /**
     * Get [B]READ view of roots
     *
     * @throws AssigningNullToNotNullableException
     * @throws UnknownRelationException
     *
     * @return View
     */
    public function index()
    {
        $check_permission_redirect = Alder::checkPermission('manage options');
        if($check_permission_redirect !== true) {
            return $check_permission_redirect;
        }
        /* Get leaf type with custom modifiers */
        $root_types = RootType::with('roots')->get();
        
        $pages = Leaf::whereLeafTypeId(LeafType::whereSlug('pages')->value('id'))
            ->whereStatusId(LeafStatus::whereSlug('published')->value('id'))
            ->get();
        $index_pages = [];
        foreach ($pages as $page) {
            $page = Alder::populateWithLCMV($page, $page->leaf_type);
            if ($page->template == 'index')
                $index_pages[] = $page;
        }

        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        return view('alder::bread.roots.browse')->with([
            'root_types' => $root_types,
            'admin_menu_items' => $admin_menu_items,
            'index_pages' => $index_pages,
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
    
    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function update(Request $request)
    {
        $root = Alder::setRootValue($request->param, $request->value);
        return
            $root
                ? Alder::returnResponse(
                $request->ajax(),
                __('alder::generic.successfully_updated') . " $root->title",
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

    public function destroy(Request $request, $id)
    {

    }
}
