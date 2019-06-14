<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafCustomModifier;
use Webcosmonauts\Alder\Models\LeafType;

class LCMController extends BaseController
{
    public function index(Request $request)
    {
        $LCMs = LeafCustomModifier::all();

        $admin_menu_items = Alder::getMenuItems();

        return view('alder::bread.LCMs.browse')->with(compact(
            'LCMs', 'admin_menu_items'
        ));
    }

    public function show(Request $request)
    {

    }

    public function create(Request $request)
    {
        $leaf_types = LeafType::all();
        $admin_menu_items = Alder::getMenuItems();
        return view('alder::bread.LCMs.edit')->with([
            'edit' => false,
            'leaf_types' => $leaf_types,
            'admin_menu_items' => $admin_menu_items,
        ]);
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $LCM = new LeafCustomModifier();
                foreach (['title', 'slug', 'group_title', 'group_slug'] as $field)
                    $LCM->$field = $request->$field;
                $LCM->modifiers = json_decode($request->data);
                $LCM->save();

                return Alder::returnRedirect(
                    $request->ajax(),
                    __('alder::generic.successfully_created') . " $LCM->title",
                    route("alder.LCMs.index"),
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

    public function edit(Request $request, $param)
    {
        if (is_int($param))
            $LCM = LeafCustomModifier::findOrFail($param);
        else
            $LCM = LeafCustomModifier::where('slug', $param)->firstOrFail();

        $leaf_types = LeafType::all();
        $admin_menu_items = Alder::getMenuItems();
        return view('alder::bread.LCMs.edit')->with([
            'edit' => true,
            'LCM' => $LCM,
            'leaf_types' => $leaf_types,
            'admin_menu_items' => $admin_menu_items,
        ]);
    }

    public function update(Request $request, $param)
    {
        return DB::transaction(function () use ($request, $param) {
            try {
                if (is_int($param))
                    $LCM = LeafCustomModifier::findOrFail($param);
                else
                    $LCM = LeafCustomModifier::where('slug', $param)->firstOrFail();

                foreach (['title', 'slug', 'group_title', 'group_slug'] as $field)
                    $LCM->$field = $request->$field;
                $LCM->modifiers = json_decode($request->data);
                $LCM->save();

                return Alder::returnRedirect(
                    $request->ajax(),
                    __('alder::generic.successfully_updated') . " $LCM->title",
                    route("alder.LCMs.index"),
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

    public function destroy(Request $request, $param)
    {
        if (is_int($param))
            $LCM = LeafCustomModifier::findOrFail($param);
        else
            $LCM = LeafCustomModifier::where('slug', $param)->firstOrFail();

        return
            $LCM->delete()
                ? Alder::returnResponse(
                $request->ajax(),
                __('alder::messages.processing_error'), // todo deleted successfully
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
}
