<?php
namespace Webcosmonauts\Alder;

use Exception;
use http\Exception\InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use stdClass;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifier;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Models\LeafStatus;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\BaseModel;
use Webcosmonauts\Alder\Models\Root;
use Webcosmonauts\Alder\Models\RootType;

class Alder
{
    /**
     * Get LeafType from database using $param as id or name,
     * depending on its data type.
     * If its neither of those, the invalid argument exception will be thrown.
     *
     * @param int|string|LeafType $param
     *
     * @return LeafType
     */
    public function getLeafType($param) {
        if ($param instanceof LeafType)
            return $param;
        else if (is_int($param))
            return LeafType::with('LCMs')->find($param);
        else if (is_string($param))
            return LeafType::with('LCMs')->where('name', $param)->first();
        else
            throw new InvalidArgumentException();
    }
    
    /**
     * Create new LeafType and add AdminMenuItem for it
     *
     * @param string $name
     * @param string $lcm_name
     * @param array $modifiers
     * @param array|null $menu_item_values
     *
     * @return bool|mixed
     */
    public function createLeafType(string $name, string $lcm_name, array $modifiers, array $menu_item_values = null) {
        $leaf_type = LeafType::where('name', $name)->first();
        $LCM = LeafCustomModifier::where('name', $lcm_name)->first();
        if (!empty($leaf_type) || !empty($LCM))
            return false;
    
        return DB::transaction(function () use ($name, $lcm_name, $modifiers, $menu_item_values) {
            try {
                $LCM = new LeafCustomModifier();
                $LCM->name = $lcm_name;
                $LCM->modifiers = $modifiers;
                $LCM->save();
                
                $leaf_type = new LeafType();
                $leaf_type->name = $name;
                $leaf_type->LCM_id = $LCM->id;
                $leaf_type->save();
                
                $LCMV = new LeafCustomModifierValue();
                $LCMV->values = [
                    'icon' => $menu_item_values['icon'] ?? 'ellipsis-h',
                    'position' => $menu_item_values['position'] ?? 'left',
                    'order' => $menu_item_values['order'] ?? null,
                    'parent_id' => $menu_item_values['parent_id'] ?? null,
                    'is_active' => $menu_item_values['is_active'] ?? true,
                ];
                $LCMV->save();
                $leaf = new Leaf();
                $leaf->title = Str::title(str_replace('-', ' ', $name));
                $leaf->slug = $menu_item_values['slug'] ?? $name;
                $leaf->status_id = LeafStatus::where('name', 'published')->value('id');
                $leaf->leaf_type_id = LeafType::where('name', 'admin-menu-items')->value('id');
                $leaf->LCMV_id = $LCMV->id;
                $leaf->save();
                
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                return false;
            }
        });
    }
    
    /**
     * Get RootType from database using $param as id or name,
     * depending on its data type.
     * If its neither of those, the invalid argument exception will be thrown.
     *
     * @param int|string|RootType $param
     *
     * @return RootType
     */
    public function getRootType($param) {
        if ($param instanceof RootType)
            return $param;
        else if (is_int($param))
            return RootType::find($param);
        else if (is_string($param))
            return RootType::where('name', $param)->first();
        else
            throw new InvalidArgumentException();
    }
    
    /**
     * Get Root from database using $param as id or name,
     * depending on its data type.
     * If its neither of those, the invalid argument exception will be thrown.
     *
     * @param int|string $param
     *
     * @return Root
     */
    public function getRoot($param) {
        if (is_int($param))
            return Root::find($param);
        else if (is_string($param))
            return Root::where('name', $param)->first();
        else
            throw new InvalidArgumentException();
    }
    
    /**
     * Adds new root. Creates new root type if there is none with passed name.
     *
     * @param string|int|RootType $root_type
     * @param array $parameters
     *
     * @return Root
     */
    public function addRoot($root_type, array $parameters = []) {
        $rootType = $this->getRootType($root_type);
        
        return DB::transaction(function () use ($rootType, $root_type, $parameters) {
            // make new root type if necessary
            if (empty($rootType)) {
                $rootType = new RootType();
                $rootType->name = $root_type;
                $rootType->save();
            }
    
            // prepare new root
            $root = new Root();
            $root->root_type_id = $rootType->id;
            
            // fields that one is allowed to fill
            $fillables = ['name', 'value', 'order', 'capabilities', 'is_active'];
            foreach ($fillables as $field) {
                if (isset($parameters[$field]))
                    $root->$field = $parameters[$field];
            }
            
            $root->save();
            return $root;
        });
    }
    
    /**
     * Set value to existing root
     *
     * @param int|string $param Root's name or ID
     * @param string|array $value Value to set
     *
     * @return bool|Root
     */
    public function setRootValue($param, $value) {
        $root = $this->getRoot($param);
        $root->value = $value;
        return $root->save() ? $root : false;
    }
    
    /**
     * Get value of existing root
     *
     * @param int|string $param Root's name or ID
     *
     * @return string
     */
    public function getRootValue($param) {
        return $this->getRoot($param)->value;
    }
    
    /**
     * Assign value saving array's nesting
     *
     * @param $combined
     * @param $key
     * @param $value
     */
    public function arrayRecursion(&$combined, $key, $value) {
        if (is_array($value) && count($value) > 0) {
            foreach ($value as $value_key => $value_value) {
                $this->arrayRecursion($combined[$key], $value_key, $value_value);
            }
        }
        else {
            if (!isset($combined[$key]))
                $combined[$key] = $value;
            else {
                logger("Field $key already exists in another modifier");
                // TODO add notification
            }
        }
    }
    
    /**
     * Get combined LCMs of LeafType
     *
     * @param LeafType $leaf_type
     *
     * @return object
     */
    public function combineLeafTypeLCMs(LeafType $leaf_type) {
        $combined = [];
        
        foreach ($leaf_type->LCMs as $LCM) {
            $modifiers = get_object_vars($LCM->modifiers);
            
            foreach ($modifiers as $name => $modifier) {
                switch ($name) {
                    case 'leaf_type': break;
                    default:
                        foreach ($modifier as $field_name => $parameters) {
                            $this->arrayRecursion($combined[$name], $field_name, $parameters);
                        }
                }
            }
        }
        
        return $this->arrayToObject($combined);
    }
    
    /**
     * Get relations for combined parameters
     *
     * @param $params
     *
     * @return stdClass
     */
    public function getRelations($params) {
        $relations = [];
        
        foreach ($params->fields as $field_name => $field_modifiers) {
            if ($field_modifiers->type == 'relation') {
                switch ($field_modifiers->relation_type) {
                    case 'hasOne':
                        break;
                    case 'hasMany':
                        break;
                    case 'belongsTo':
                        break;
                    case 'belongsToMany':
                        break;
                    
                    default:
                }
            }
        }
        
        return $this->arrayToObject($relations);
    }
    
    /**
     * Add values from model's LCMV
     *
     * @throws AssigningNullToNotNullableException
     *
     * @param BaseModel $model
     * @param int|string|LeafType $type
     * @param object $combined
     */
    public function populateWithLCMV(BaseModel &$model, $type, $combined = null) {
        // Get instance of LeafType
        $leaf_type = $this->getLeafType($type);
        
        /**
         * Combine all LeafType's modifiers.
         *
         * If two modifiers have same fields, only first one will be used.
         * Additionally, log message and notification will be made.
         */
        $params = is_null($combined) ? $this->combineLeafTypeLCMs($leaf_type) : $combined;
        
        /**
         * Assign values to prepared parameters.
         */
        foreach ($params->fields as $field_name => $field_modifiers) {
            // If LCMV have value for parameter, assign it.
            if (isset($model->LCMV->values->$field_name) && !empty($model->LCMV->values->$field_name))
                $model->$field_name = $model->LCMV->values->$field_name;
            else {
                // assign default value, if it is set
                if (isset($field_modifiers->default))
                    $model->$field_name = $field_modifiers->default;
                // if default value is not set, assign null if possible
                else if (isset($field_modifiers->nullable) && $field_modifiers->nullable == true)
                    $model->$field_name = null;
                // if default value is not set and parameter is not nullable, throw exception
                else
                    throw new AssigningNullToNotNullableException($field_name);
            }
        }
    }
    
    /**
     * Get menu items
     *
     * @throws AssigningNullToNotNullableException
     *
     * @return Collection
     */
    public function getMenuItems() {
        $leaf_type = LeafType::with('LCMs')->where('name', 'admin-menu-items')->first();
        $leaves = Leaf::where('leaf_type_id', $leaf_type->id)->get();
        $page_type = explode('/', Route::getCurrentRoute()->uri)[1] ?? '';
        
        foreach ($leaves as &$leaf) {
            $this->populateWithLCMV($leaf, $leaf_type);
        }
        
        // Separate sections (with parent_id = null)
        list($sections, $leaves) = $leaves->partition(function ($item) {
            return $item->parent_id == null;
        });
        
        foreach ($sections as &$section) {
            $section->setAttribute('is_current', $section->slug == $page_type);
            
            // Get items for each section
            list($section->children, $leaves) = $leaves->partition(function ($item) use (&$section, $page_type) {
                if ($item->parent_id == $section->id) {
                    if (!$section->is_current)
                        $section->setAttribute('is_current', $item->slug == $page_type);
                    $item->setAttribute('is_current', $item->slug == $page_type);
                    return true;
                }
                else
                    return false;
            });
            
            // Get children for each item
            foreach ($section->children as &$menu_item) {
                list($menu_item->children, $leaves) = $leaves->partition(function ($item) use (&$section, &$menu_item, $page_type) {
                    if ($item->parent_id == $menu_item->id) {
                        if (!$section->is_current)
                            $section->setAttribute('is_current', $item->slug == $page_type);
                        if (!$menu_item->is_current)
                            $menu_item->setAttribute('is_current', $item->slug == $page_type);
                        $item->setAttribute('is_current', $item->slug == $page_type);
                        return true;
                    }
                    else
                        return false;
                });
            }
        }
        
        return $sections;
    }
    
    /**
     * Convert array to object recursively
     *
     * @param array $array Array to cast
     * @return stdClass|array Object casted from array or array value of parameter
     */
    function arrayToObject($array) {
        if (!is_array($array)) return $array;
        foreach ($array as &$item) {
            $item = $this->arrayToObject($item);
        }
        return (object) $array;
    }
    
    /**
     * Deploy Alder routes
     */
    public function routes() {
        require __DIR__.'/../routes/alder.php';
    }
    
    /**
     * Return JSON response or redirect back with flashed message
     *
     * @param bool $isAJAX
     * @param string $message
     * @param bool $success
     * @param string $alert_type
     * @param string|null $exception
     *
     * @return JsonResponse|RedirectResponse
     */
    public function returnResponse(bool $isAJAX, string $message, bool $success = true, string $alert_type = 'primary', string $exception = null) {
        return $isAJAX ? response()->json([
                'success' => $success,
                'alert-type' => $alert_type,
                'message' => $message,
                'exception' => $exception,
            ])
            : back()->with([
                'alert-type' => $alert_type,
                'message' => $message,
                'exception' => $exception,
            ]);
    }
    
    /**
     * Return JSON response or redirect back with flashed message
     *
     * @param bool $isAJAX
     * @param string $message
     * @param string $redirectTo
     * @param bool $success
     * @param string $alert_type
     * @param string|null $exception
     *
     * @return JsonResponse|RedirectResponse
     */
    public function returnRedirect(bool $isAJAX, string $message, string $redirectTo, bool $success = true, string $alert_type = 'primary', string $exception = null) {
        return $isAJAX ? response()->json([
                'success' => $success,
                'alert-type' => $alert_type,
                'message' => $message,
                'exception' => $exception,
                'redirect' => $redirectTo,
            ])
            : redirect($redirectTo)->with([
                'alert-type' => $alert_type,
                'message' => $message,
                'exception' => $exception,
            ]);
    }
}
