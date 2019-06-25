<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Exceptions\UnknownRelationException;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\Root;
use Webcosmonauts\Alder\Models\RootType;

class MenuController extends BaseController
{
    public function menuCreate(Request $request){
        $leaf_types = LeafType::with('leaves')->where('is_accessible',true)->get();

        $branchType = $this->getBranchType($request);

        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);

        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf_type);

        /* Get branch instance and all its leaves */
        $branch = Leaf::with('LCMV')->where('leaf_type_id', $leaf_type->id)->get();

        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        $cont_id = LeafType::where('slug', 'menus')->value('id');
        $menu = Leaf::where('leaf_type_id',$cont_id)->get()->first();

        $comtent = '';



        return view('alder::bread.menus.edit')->with([
            'leaves' => $branch,
            'leaf_type' => $leaf_type,
            'leaf_types' => $leaf_types,
            'admin_menu_items' => $admin_menu_items,
            'edit'=>false,
            'params'=>$params,
            'comtent'=>$comtent
        ]);
    }


    public function store(Request $request){

        $branchType = $this->getBranchType($request);
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf_type);

        return $this->createMenu(false, $request, $leaf_type, $params);
    }


    public function update(Request $request, $id){



        $branchType = $this->getBranchType($request);
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf_type);

        return $this->createMenu(true, $request, $leaf_type, $params , $id);
    }
    public function show(Request $request, $id) {


        $menu = Leaf::where('id',$id)->get()->first();

        $content = json_decode($menu->content);

        return view('alder::bread.menus.read')->with([
            'admin_menu_items' => Alder::getMenuItems(),
            'request' => $request,
            'menu' => $menu,
            'content' => $content,
        ]);
    }



    public function deleteMenu(Request $request){



        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        return view('alder::bread.menus.edit')->with([
            'admin_menu_items' => $admin_menu_items,
        ]);
    }


    public function editMenu(Request $request, $id){
        $leaf_types = LeafType::with('leaves')->where('is_accessible',true)->get();

        $branchType = $this->getBranchType($request);

        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);

        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf_type);

        /* Get branch instance and all its leaves */
        $branch = Leaf::with('LCMV')->where('leaf_type_id', $leaf_type->id)->get();

        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        $menu = Leaf::where('id',$id)->get()->first();

        $comtent = $menu->content;


        return view('alder::bread.menus.edit')->with([
            'leaves' => $branch,
            'leaf_type' => $leaf_type,
            'admin_menu_items' => $admin_menu_items,
            'edit'=>true,
            'params'=>$params,
            'menu' => $menu,
            'comtent'=>$comtent,
            'leaf_types'=>$leaf_types
        ]);
    }


    private function createMenu ($edit, Request $request, LeafType $leaf_type, $params, $id = null) {
        return DB::transaction(function () use ($edit, $request, $leaf_type, $params, $id) {
            try {


                $cont_id = LeafType::where('slug', 'menus')->value('id');

                $menu = $edit ? Leaf::where('id',$id)->get()->first() : new Leaf();

                $LCMV = $edit ? $menu->LCMV : new LeafCustomModifierValue();

                $menu->title = $request->title;
                $menu->slug = $request->slug;
                $menu->content = $request['content'];
                $menu->is_accessible = 1;
                $edit ? : $menu->status_id = 5;
                $edit ? : $menu->leaf_type_id = $cont_id;
                $edit ? : $menu->user_id = Auth::user()->id;
                $edit ? : $menu->created_at = date("Y-m-d H:i:s");
                $menu->updated_at = date("Y-m-d H:i:s");
                $menu->revision = 0;


                $menu->save();

                $id = $menu->id;

                return \Webcosmonauts\Alder\Facades\Alder::returnRedirect(
                    $request->ajax(),
                    __('alder::generic.successfully_'
                        . ('created')) . " $menu->title",
                    route("alder.menus.index"),
                    true,
                    'success'
                );
            } catch (\Exception $e) {
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
    private function addValue($request, $params, $values = []) {
        foreach ($params as $field_name => $modifiers) {
            if (!isset($modifiers->type)) {
                $values[$field_name] = $this->addValue($request, $modifiers->fields);
            }
            else {
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
        }
        return $values;
    }

}
