<?php

namespace Webcosmonauts\Alder\Http\Controllers;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Http\Controllers\LeavesController\LeafEntityController;
use Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifier;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Models\LeafStatus;

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
        $active_theme = Alder::getRootValue('active-theme');
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
            return response()->view('themes.'.$active_theme.'.404');
        
        $leaf = Alder::populateWithLCMV($leaf, $leaf->leaf_type);

        $leaf_view_renderer = TemplateController::getViewForLeaf($leaf);

        return view($leaf_view_renderer, compact('leaf'));
    }
    
    /**
     * Get category page
     *
     * @throws \Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownConditionOperatorException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownConditionParameterException
     * @throws \Webcosmonauts\Alder\Exceptions\UnknownRelationException
     *
     * @param Request $request
     * @param $leaf_type
     * @param $category
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCategoryLeafs(Request $request, $leaf_type, $category) {
        $leaf_type = Alder::getLeafTypeFromTranslation($leaf_type);
        $category = Leaf::whereTranslation('slug', $category)->first();
        if (!$category)
            return view('themes.nimoz.404');
        
        $leaves = Leaf::where('leaf_type_id', $leaf_type->id)
            ->where('status_id', LeafStatus::where('slug', 'published')->value('id'))
            ->get();
        $combined = Alder::combineLCMs($leaf_type)->lcm;
        
        $filtered = new Collection();
        foreach ($leaves as &$leaf) {
            $leaf = Alder::populateWithLCMV($leaf, $leaf->leaf_type, $combined);
            if (isset($leaf->categories) && !empty($leaf->categories) && in_array($category->id, $leaf->categories->pluck('id')->toArray()))
                $filtered->push($leaf);
        }
        
        $leaf_view_renderer = TemplateController::getViewForLeaf($category);
        $leaves_per_page = Alder::getRootValue('leaves_per_page');
        
        return view($leaf_view_renderer)->with([
            'leaves' => Alder::paginate($filtered, $leaves_per_page),
            'category' => $category,
        ]);
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
