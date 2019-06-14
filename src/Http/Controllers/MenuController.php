<?php

namespace Webcosmonauts\Alder\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Exceptions\UnknownRelationException;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafType;

class MenuController extends BaseController
{
    public function menuCreate(Request $request){
        $leaf_types = LeafType::with('leaves')->where('is_accessible',true)->get();

        $branchType = $this->getBranchType($request);

        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);

        /* Get combined parameters of all LCMs */
        $params = Alder::combineLeafTypeLCMs($leaf_type);

        /* Get branch instance and all its leaves */
        $branch = Leaf::with('LCMV')->where('leaf_type_id', $leaf_type->id)->get();

        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();

        return view('alder::bread.menus.edit')->with([
            'leaves' => $branch,
            'leaf_type' => $leaf_type,
            'leaf_types' => $leaf_types,
            'admin_menu_items' => $admin_menu_items,
            'edit'=>false,
            'params'=>$params
        ]);
    }
}
