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
     * @throws \Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownConditionOperatorException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownConditionParameterException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownRelationException
     *
     * @param Request $request
     * @param String $slug
     *
     * @return View
     */
    public function index(Request $request, $slug)
    {
        $uri = explode('/', $request->path())[0];
        if ($uri == 'posts' || $uri == 'posty')
            $uri = 'posts';
        else if ($uri == 'reports' || $uri == 'raporty')
            $uri = 'reports';
        else
            $uri = 'pages';
        
        if (is_int((int)$slug) && (int)$slug > 0){
            $leaf = LeafEntityController::getLeaf($slug, $uri);
        }
        elseif (is_string($slug)){
            $leaf = LeafEntityController::getLeafBySlag($slug, $uri);
        }
        if (!isset($leaf) || !$leaf)
            return response()->view('themes.nimoz.404');
        
        $leaf = Alder::populateWithLCMV($leaf, $leaf->leaf_type);
        
        $leaf_view_renderer = TemplateController::getViewForLeaf($leaf);

        return view($leaf_view_renderer, compact('leaf'));
    }

    /**
     * Get leaf type
     *
     * @param Request $request
     * @return View
     * @throws AssigningNullToNotNullableException
     */
    public function leafTypeShow(Request $request)
    {
        $uri = explode('/', $request->path())[0];
        if ($uri = 'posty')
            $uri = 'posts';
        $leaves = LeafEntityController::getLeavesByType($uri);
        dd($leaves);
        
        
        
        if(!empty($uri) && !empty($slug)){
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
