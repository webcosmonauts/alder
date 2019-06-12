<?php

namespace Webcosmonauts\Alder\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Http\Controllers\LeavesController\LeafEntityController;
use Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifier;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;

class LeafController extends Controller
{
    private $theme;

    public function __construct()
    {
        $this->theme = Alder::getRootValue('active-theme');
    }


    /**
     * Get single leaf view
     *
     * @param Request $request
     * @param String $slug
     * @param LeafController $theme
     * @return View
     * @throws AssigningNullToNotNullableException
     */
    public function index(Request $request, $slug, LeafController $theme)
    {
        if(is_int((int)$slug) && (int)$slug > 0){
            $leaf = LeafEntityController::getLeaf($slug);
        }
        elseif(is_string($slug)){
            $leaf = LeafEntityController::getLeafBySlag($slug);
        }

        $lcmv = LeafCustomModifierValue::where('id', '=', $leaf->LCMV_id)->firstOrFail();

        $rendered_leaf_template = TemplateController::getViewForLeaf($lcmv->template);
        
        $template = LeafEntityController::getLeafTemplate($leaf->id);

        return view("templates." . $this->theme.$template, compact('leaf', 'lcmv'));
    }



}
