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
    public function index(Request $request, $slug)
    {
        if(is_int((int)$slug) && (int)$slug > 0){
            $leaf = LeafEntityController::getLeaf($slug);
        }
        elseif(is_string($slug)){
            $leaf = LeafEntityController::getLeafBySlag($slug);
        }
        //dd($leaf);
        $leaf_view_renderer = TemplateController::getViewForLeaf($leaf);

        return view($leaf_view_renderer, compact('leaf'));
    }

    /**
     * Get leaf type
     *
     * @param Request $request
     * @param String $slug
     * @param LeafController $theme
     * @return View
     * @throws AssigningNullToNotNullableException
     */
    public function leafTypeShow(Request $request,$leaf_type, $slug)
    {
        $leaves = LeafEntityController::getLeavesByType($leaf_type);
        dd($leaves);
        if(!empty($leaf_type) && !empty($slug)){
            if(is_int((int)$slug) && (int)$slug > 0){
                $leaf = LeafEntityController::getLeaf($slug);
            }
            elseif(is_string($slug)){
                $leaf = LeafEntityController::getLeafBySlag($slug);
            }

            $leaf_view_renderer = TemplateController::getViewForLeaf($leaf);


            return view($leaf_view_renderer, compact('leaf', 'lcmv'));
        }

    }




}
