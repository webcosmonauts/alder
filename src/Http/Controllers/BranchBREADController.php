<?php
    
    namespace Webcosmonauts\Alder\Http\Controllers;
    
    use Exception;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\View\View;
    use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
    use Webcosmonauts\Alder\Models\Leaf;
    use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
    use Webcosmonauts\Alder\Facades\Alder;
    use Webcosmonauts\Alder\Models\LeafType;

    class BranchBREADController extends BaseController
    {
        /**
         * Get [B]READ view of branch with leaves
         *
         * @throws AssigningNullToNotNullableException
         * @param Request $request
         * @return View
         */
        public function index(Request $request) {
            $branchType = $this->getBranchType($request);
            
            /* Get leaf type with custom modifiers */
            $leaf_type = Alder::getLeafType($branchType);
            
            /* Get combined parameters of all LCMs */
            $params = Alder::combineLeafTypeLCMs($leaf_type);
            
            /* Get branch instance and all its leaves */
            $branch = Leaf::with('LCMV')->where('leaf_type_id', $leaf_type->id)->get();
            
            /* Populate models with values from LCMV */
            foreach ($branch as &$leaf)
                Alder::populateWithLCMV($leaf, $leaf_type, $params);
            
            /* Get admin panel menu items */
            $admin_menu_items = Alder::getMenuItems();

            /* Get custom browse blade if view exists */
            if(view()->exists('alder::'.$leaf_type->name.'.browse')){
                return view('alder::'.$leaf_type->name.'.browse')->with([
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
         * @param Request $request
         * @param string $slug
         * @return View
         */
        public function show(Request $request, string $slug) {
            /* Get leaf */
            $leaf = Leaf::with(['leaf_type', 'LCMV'])->where('slug', $slug)->first();
            if (!$leaf)
                abort(404);
            
            /* Get combined parameters of all LCMs */
            $params = Alder::combineLeafTypeLCMs($leaf->leaf_type);

            /* Populate model with values from LCMV */
            Alder::populateWithLCMV($leaf, $leaf->leaf_type, $params);

            $leaf_type = $leaf->leaf_type->name;


            /* Get admin panel menu items */
            $admin_menu_items = Alder::getMenuItems();

            /* Get custom read blade if view exists */
            if(view()->exists('alder::'.$leaf_type.'.read')){
                return view('alder::'.$leaf_type.'.read')->with([
                    'leaf' => $leaf,
                    'params' => $params,
                    'admin_menu_items' => $admin_menu_items,
                ]);
            }
            /* If view does not exists, show bread blade */
            else{
                return view('alder::bread.read')->with([
                    'leaf' => $leaf,
                    'params' => $params,
                    'admin_menu_items' => $admin_menu_items,
                ]);
            }

        }
    
        /**
         * Get [C]RUD view
         *
         * @throws AssigningNullToNotNullableException
         * @param Request $request
         * @return View
         */
        public function create(Request $request) {
            $branchType = $this->getBranchType($request);
            
            /* Get leaf type with custom modifiers */
            $leaf_type = Alder::getLeafType($branchType);
            
            /* Get combined parameters of all LCMs */
            $params = Alder::combineLeafTypeLCMs($leaf_type);
            
            $relations = Alder::getRelations($params);
            
            /* Get admin panel menu items */
            $admin_menu_items = Alder::getMenuItems();

            /* Get custom edit blade if view exists */
            if(view()->exists('alder::'.$leaf_type->name.'.read')){
                return view('alder::'.$leaf_type->name.'.edit')->with([
                    'edit' => false,
                    'leaf_type' => $leaf_type,
                    'admin_menu_items' => $admin_menu_items,
                    'params' => $params,
                ]);
            }
            /* If view does not exists, show bread blade */
            else{
                return view('alder::bread.edit')->with([
                    'edit' => false,
                    'leaf_type' => $leaf_type,
                    'admin_menu_items' => $admin_menu_items,
                    'params' => $params,
                ]);
            }

        }
    
        /**
         * Create and store new leaf from request
         *
         * @param Request $request
         * @return mixed
         */
        public function store(Request $request) {
            $branchType = $this->getBranchType($request);
            
            /* Get leaf type with custom modifiers */
            $leaf_type = Alder::getLeafType($branchType);
            
            /* Get combined parameters of all LCMs */
            $params = Alder::combineLeafTypeLCMs($leaf_type);
            
            return $this->editLeaf(false, $request, $leaf_type, $params);
        }
    
        /**
         * Get BR[E]AD view for leaf
         *
         * @throws AssigningNullToNotNullableException
         *
         * @param Request $request
         * @param string $slug
         *
         * @return View
         */
        public function edit(Request $request, string $slug) {
            /* Get leaf */
            $leaf = Leaf::with(['leaf_type', 'LCMV'])->where('slug', $slug)->firstOrFail();
    
            /* Get combined parameters of all LCMs */
            $params = Alder::combineLeafTypeLCMs($leaf->leaf_type);
            
            $relations = Alder::getRelations($params);
            dd($relations);
    
            /* Get admin panel menu items */
            $admin_menu_items = Alder::getMenuItems();
    
            return view('alder::bread.edit')->with([
                'edit' => true,
                'leaf_type' => $leaf->leaf_type,
                'leaf' => $leaf,
                'admin_menu_items' => $admin_menu_items,
                'params' => $params,
            ]);
        }
    
        /**
         * Update existing leaf (CR[U]D)
         *
         * @param Request $request
         * @param $slug
         *
         * @return mixed
         */
        public function update(Request $request, $slug) {
            /* Get leaf */
            $leaf = Leaf::with(['leaf_type', 'LCMV'])->where('slug', $slug)->first();
            if (!$leaf)
                abort(404);
    
            /* Get combined parameters of all LCMs */
            $params = Alder::combineLeafTypeLCMs($leaf->leaf_type);
    
            return $this->editLeaf(true, $request, $leaf->leaf_type, $params, $leaf);
        }
    
        /**
         * Delete leaf (CRU[D])
         *
         * @param Request $request
         * @param $slug
         *
         * @return JsonResponse|RedirectResponse
         */
        public function destroy(Request $request, $slug) {
            return
                Leaf::where('slug', $slug)->delete()
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
        
        private function editLeaf(bool $edit, Request $request, LeafType $leaf_type, $params, Leaf $edit_leaf = null) {
            return DB::transaction(function () use ($edit, $request, $leaf_type, $params, $edit_leaf) {
                try {
                    $leaf = $edit ? $edit_leaf : new Leaf();
                    $LCMV = $edit ? $leaf->LCMV : new LeafCustomModifierValue();
                    
                    $values = [];
                    foreach ($params->fields as $field_name => $modifiers) {
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
                    $LCMV->values = $values;
                    $LCMV->save();
            
                    $leaf->title = $request->title;
                    $leaf->slug = $request->slug;
                    $leaf->content = $request->get('content');
                    $leaf->user_id = $request->user_id;
                    $leaf->status_id = 5;
                    $leaf->leaf_type_id = $leaf_type->id;
                    $leaf->LCMV_id = $LCMV->id;
                    $leaf->save();
            
                    if ($edit)
                        return Alder::returnRedirect(
                            $request->ajax(),
                            __('alder::generic.successfully_updated') . " $leaf->title",
                            route("alder.$leaf_type->name.index"),
                            true,
                            'success'
                        );
                    else
                        return Alder::returnResponse(
                            $request->ajax(),
                            __('alder::generic.successfully_created') . " $leaf->title",
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
    }
