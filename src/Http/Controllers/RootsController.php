<?php


namespace Webcosmonauts\Alder\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Exceptions\UnknownRelationException;
use Webcosmonauts\Alder\Facades\Alder;
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
    
    /**
     * @param Request $request
     * @param $param
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
