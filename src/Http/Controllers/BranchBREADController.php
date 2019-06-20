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
use Webcosmonauts\Alder\Exceptions\UnknownConditionOperatorException;
use Webcosmonauts\Alder\Exceptions\UnknownConditionParameterException;
use Webcosmonauts\Alder\Exceptions\UnknownRelationException;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\LeafType;

class BranchBREADController extends BaseController
{
    /**
     * Get [B]READ view of branch with
     *
     * @throws AssigningNullToNotNullableException
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     * @throws UnknownRelationException
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request) {
        $branchType = $this->getBranchType($request);
        
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
        
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf_type);
        
        /* Get branch instance and all its leaves */
        $branch = Leaf::with('LCMV')->where('leaf_type_id', $leaf_type->id)->get();
        
        /* Populate models with values from LCMV */
        foreach ($branch as &$leaf)
            $leaf = Alder::populateWithLCMV($leaf, $leaf_type, $params->lcm);
        
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();
        
        /* Get custom browse blade if view exists */
        if(view()->exists('alder::'.$leaf_type->slug.'.browse')){
            return view('alder::'.$leaf_type->slug.'.browse')->with([
                'leaves' => $branch,
                'leaf_type' => $leaf_type,
                'admin_menu_items' => $admin_menu_items,
                'params' => $params,
            ]);
        }
        /* If view does not exists, show bread blade */
        else{
            return view('alder::bread.browse')->with([
                'leaves' => $branch,
                'leaf_type' => $leaf_type,
                'admin_menu_items' => $admin_menu_items,
                'params' => $params,
            ]);
        }
        
        
    }
    
    /**
     * Get C[R]UD view for a leaf
     *
     * @throws AssigningNullToNotNullableException
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     * @throws UnknownRelationException
     *
     * @param Request $request
     * @param int $id
     *
     * @return View
     */
    public function show(Request $request, int $id) {
        /* Get leaf */
        $leaf = Leaf::with(['leaf_type', 'LCMV'])->findOrFail($id);
        
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf->leaf_type);
        
        /* Populate model with values from LCMV */
        $leaf = Alder::populateWithLCMV($leaf, $leaf->leaf_type, $params->lcm);
        
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();
        
        /* Get custom read blade if view exists */
        $view = "alder::bread.".$leaf->leaf_type->slug.".read";
        if (!view()->exists($view))
            $view = 'alder::bread.read';
        
        return view($view)->with([
            'leaf' => $leaf,
            'params' => $params,
            'admin_menu_items' => $admin_menu_items,
        ]);
    }
    
    /**
     * Get [C]RUD
     *
     * @throws AssigningNullToNotNullableException
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     * @throws UnknownRelationException
     *
     * @param Request $request
     *
     * @return View
     */
    public function create(Request $request) {
        $branchType = $this->getBranchType($request);
        
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
        
        /* Get combined parameters of all LCMs */
        $params = Alder::prepareLCMs($leaf_type->LCMs);
        
        $combined = Alder::combineLCMs($leaf_type, $params);
        
        $relations = Alder::getRelations($combined->lcm);
        
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();
        
        /* Get custom read blade if view exists */
        $view = "alder::bread.$leaf_type->slug.edit";
        if (!view()->exists($view))
            $view = 'alder::bread.edit';
        
        return view($view)->with([
            'edit' => false,
            'leaf_type' => $leaf_type,
            'admin_menu_items' => $admin_menu_items,
            'params' => $params,
            'relations' => $relations,
        ]);
    }
    
    /**
     * Create and store new leaf from request
     *
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function store(Request $request) {
        $branchType = $this->getBranchType($request);
        
        /* Get leaf type with custom modifiers */
        $leaf_type = Alder::getLeafType($branchType);
        
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf_type);
        
        return $this->editLeaf(false, $request, $leaf_type, $params);
    }
    
    /**
     * Get BR[E]AD view for leaf
     *
     * @throws AssigningNullToNotNullableException
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     * @throws UnknownRelationException
     *
     * @param Request $request
     * @param int $id
     *
     * @return View
     */
    public function edit(Request $request, int $id) {
        /* Get leaf */
        $leaf = Leaf::with(['leaf_type', 'LCMV'])->findOrFail($id);
        
        /* Get combined parameters of all LCMs */
        $params = Alder::prepareLCMs($leaf->leaf_type->LCMs);
        
        $combined = Alder::combineLCMs($leaf->leaf_type, $params, true, $leaf);
        
        /* Populate model with values from LCMV */
        $leaf = Alder::populateWithLCMV($leaf, $leaf->leaf_type, $combined->lcm);
        
        $relations = Alder::getRelations(Alder::combineLCMs($leaf->leaf_type, $params)->lcm);
        
        /* Get admin panel menu items */
        $admin_menu_items = Alder::getMenuItems();
        
        /* Get custom read blade if view exists */
        $view = "alder::bread.".$leaf->leaf_type->slug.".edit";
        if (!view()->exists($view))
            $view = 'alder::bread.edit';
        
        return view($view)->with([
            'edit' => true,
            'leaf_type' => $leaf->leaf_type,
            'leaf' => $leaf,
            'admin_menu_items' => $admin_menu_items,
            'params' => $params,
            'relations' => $relations,
        ]);
    }
    
    /**
     * Update existing leaf (CR[U]D)
     *
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     *
     * @param Request $request
     * @param int $id
     *
     * @return mixed
     */
    public function update(Request $request, int $id) {
        /* Get leaf */
        $leaf = Leaf::with(['leaf_type', 'LCMV'])->findOrFail($id);
        
        /* Get combined parameters of all LCMs */
        $params = Alder::combineLCMs($leaf->leaf_type);
        
        return $this->editLeaf(true, $request, $leaf->leaf_type, $params, $leaf);
    }
    
    /**
     * Delete leaf (CRU[D])
     *
     * @param Request $request
     * @param int $id
     *
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(Request $request, int $id) {

        $branchType = $this->getBranchType($request);
        $leaf_type = Alder::getLeafType($branchType);

        return
            Leaf::where('leaf_type_id', $leaf_type->id)->find($id)->delete()
                ? Alder::returnResponse(
                $request->ajax(),
                __('alder::messages.delete_successfully'),
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
    
    /**
     * Edit or create leaf
     *
     * @param bool $edit
     * @param Request $request
     * @param LeafType $leaf_type
     * @param $params
     * @param Leaf|null $edit_leaf
     *
     * @return mixed
     */
    private function editLeaf(bool $edit, Request $request, LeafType $leaf_type, $params, Leaf $edit_leaf = null) {
        return DB::transaction(function () use ($edit, $request, $leaf_type, $params, $edit_leaf) {
            try {
                $leaf = $edit ? $edit_leaf : new Leaf();
                $LCMV = $edit ? $leaf->LCMV : new LeafCustomModifierValue();
                $LCMV->values = $this->addValue(json_decode($request->lcm), $params->lcm);
                $LCMV->save();
                
                $leaf->title = $request->title;
                $leaf->slug = $request->slug;
                $leaf->content = $request->get('content');
                $leaf->user_id = Auth::user()->id;
                $leaf->status_id = $request->status_id;
                $leaf->leaf_type_id = $leaf_type->id;
                $leaf->LCMV_id = $LCMV->id;
                $leaf->save();
                
                return Alder::returnRedirect(
                    $request->ajax(),
                    __('alder::generic.successfully_'
                        . ($edit ? 'updated' : 'created')) . " $leaf->title",
                    route("alder.$leaf_type->slug.index"),
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
    
    /**
     * Prepare values for LCMV
     *
     * @throws AssigningNullToNotNullableException
     *
     * @param $request
     * @param $params
     * @param array $values
     *
     * @return array
     */
    private function addValue($request, $params, $values = []) {
        foreach ($params as $field_name => $modifiers) {
            if (!isset($modifiers->type)) {
                $values[$field_name] = $this->addValue($request->$field_name, $modifiers->fields);
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
