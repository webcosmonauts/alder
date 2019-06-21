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
use Webcosmonauts\Alder\Exceptions\UnknownConditionOperatorException;
use Webcosmonauts\Alder\Exceptions\UnknownConditionParameterException;
use Webcosmonauts\Alder\Exceptions\UnknownRelationException;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafCustomModifier;
use Webcosmonauts\Alder\Models\LeafCustomModifierValue;
use Webcosmonauts\Alder\Models\LeafStatus;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\BaseModel;
use Webcosmonauts\Alder\Models\Root;
use Webcosmonauts\Alder\Models\RootType;
use Webcosmonauts\Alder\Models\User;

class Alder
{
    /**
     * Get LeafType from database using $param as id or slug,
     * depending on its data type.
     * If its neither of those, the invalid argument exception will be thrown.
     *
     * @param int|string|LeafType $param
     *
     * @return LeafType
     */
    public function getLeafType($param)
    {
        if ($param instanceof LeafType)
            return $param;
        else if (is_int($param))
            return LeafType::with('LCMs')->find($param);
        else if (is_string($param))
            return LeafType::with('LCMs')->where('slug', $param)->first();
        else
            throw new InvalidArgumentException();
    }

    /**
     * Create new LeafType and add AdminMenuItem for it
     *
     * @param string $title
     * @param string $slug
     * @param string $lcm_title
     * @param string $lcm_slug
     * @param array $modifiers
     * @param array|null $menu_item_values
     *
     * @return bool|mixed
     */
    public function createLeafType(string $title, string $slug, string $lcm_title, string $lcm_slug, array $modifiers, array $menu_item_values = null)
    {
        $leaf_type = LeafType::where('slug', $slug)->first();
        $LCM = LeafCustomModifier::where('slug', $lcm_slug)->first();
        if (!empty($leaf_type) || !empty($LCM))
            return false;

        return DB::transaction(function () use ($title, $slug, $lcm_title, $lcm_slug, $modifiers, $menu_item_values) {
            try {
                $LCM = new LeafCustomModifier();
                $LCM->title = $lcm_title;
                $LCM->slug = $lcm_slug;
                $LCM->modifiers = $modifiers;
                $LCM->save();

                $leaf_type = new LeafType();
                $leaf_type->title = $title;
                $leaf_type->slug = $slug;
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
                $leaf->title = $title;
                $leaf->slug = $menu_item_values['slug'] ?? $slug;
                $leaf->status_id = LeafStatus::where('slug', 'published')->value('id');
                $leaf->leaf_type_id = LeafType::where('slug', 'admin-menu-items')->value('id');
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
    public function getRootType($param)
    {
        if ($param instanceof RootType)
            return $param;
        else if (is_int($param))
            return RootType::find($param);
        else if (is_string($param))
            return RootType::where('slug', $param)->first();
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
    public function getRoot($param)
    {
        if (is_int($param))
            return Root::find($param);
        else if (is_string($param))
            return Root::where('slug', $param)->first();
        else
            throw new InvalidArgumentException();
    }

    /**
     * Adds new root. Creates new root type if there is none with passed name.
     *
     * @param string|int|RootType $slug
     * @param string $title
     * @param array $parameters
     *
     * @return Root
     */
    public function addRoot($slug, string $title, array $parameters = [])
    {
        $rootType = $this->getRootType($slug);

        return DB::transaction(function () use ($rootType, $title, $slug, $parameters) {
            // make new root type if necessary
            if (empty($rootType)) {
                $rootType = new RootType();
                $rootType->title = $title;
                $rootType->slug = $slug;
                $rootType->save();
            }

            // prepare new root
            $root = new Root();
            $root->root_type_id = $rootType->id;

            // fields that one is allowed to fill
            $fillables = ['title', 'slug', 'input_type',
                'value', 'options', 'order', 'capabilities', 'is_active'];
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
    public function setRootValue($param, $value)
    {
        $root = $this->getRoot($param);
        $root->value = $value;
        return $root->save() ? $root : false;
    }

    /**
     * Get value of existing root
     *
     * @param int|string $param Root's name or ID
     *
     * @return mixed|null
     */
    public function getRootValue($param) {
        $root = $this->getRoot($param);
        return $root ? $root->value : null;
    }

    /**
     * Get values of collection of roots
     *
     * @param Collection $collection Collection of roots
     *
     * @return object
     */
    public function getRootsValues($collection)
    {
        $result = [];

        foreach ($collection as $root) {
            $result[$root->slug] = $root->value;
        }

        return $this->arrayToObject($result);
    }

    /**
     * Assign value saving array's nesting
     *
     * @param $combined
     * @param $key
     * @param $value
     */
    public function arrayRecursion(&$combined, $key, $value)
    {
        if (is_array($value) && count($value) > 0) {
            foreach ($value as $value_key => $value_value) {
                $this->arrayRecursion($combined[$key], $value_key, $value_value);
            }
        } else {
            if (!isset($combined[$key]))
                $combined[$key] = $value;
            else {
                logger("Field $key already exists in another modifier");
                // TODO add notification
            }
        }
    }

    /**
     * Prepare all LCMs for edit blade
     *
     * @param $LCMs Collection of LCMs to process
     *
     * @return stdClass
     */
    public function prepareLCMs($LCMs)
    {
        $prepared = [];

        foreach ($LCMs as $LCM) {
            foreach ($LCM->modifiers as $key => $modifier)
                $prepared[$LCM->slug][$key] = $modifier;
        }

        return $this->arrayToObject($prepared);
    }

    /**
     * Combine all LCM fields and conditions with checking
     * conditions and accumulating BREAD fields.
     *
     * Result object used to populate leaf with its LCMV.
     *
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     *
     * @param LeafType $leaf_type Type of leaf that is being loaded
     * @param $prepared |null Result of 'prepareLCMs' function
     * @param bool $edit Used to prevent condition check for leaf that is being created
     * @param Leaf|null $leaf Leaf, which LCMV is used for conditions check
     *
     * @return stdClass
     */
    public function combineLCMs(LeafType $leaf_type, $prepared = null, bool $edit = false, Leaf $leaf = null)
    {
        $combined = [
            'lcm' => [],
            'bread' => [],
            'conditions' => [],
        ];

        if (is_null($prepared))
            $prepared = $this->prepareLCMs($leaf_type->LCMs);

        foreach ($prepared as $group) {
            // conditions check
            foreach ($group->conditions as $condition) {
                switch ($condition->parameter) {
                    case 'leaf-type':
                        if (!$this->isNotCheck($condition->value, $condition->operator, $leaf_type->slug))
                            continue 3; // go to next LCM
                        break;
                    case 'page-template':
                        if (!$edit)
                            continue 2; // go to next condition
                        else {
                            if (!$this->isNotCheck($condition->value, $condition->operator, $leaf->LCMV->values->template))
                                continue 3; // go to next LCM
                        }
                        break;
                    default:
                        throw new UnknownConditionParameterException();
                }
            }

            foreach ($group as $name => $modifier) {
                if (!isset($combined[$name]))
                    $combined[$name] = [];

                switch ($name) {
                    // if some modifiers need custom handling put cases here
                    case 'bread':
                        foreach ($modifier as $method_name => $method_parameters) {
                            if (!isset($combined[$name][$method_name]))
                                $combined[$name][$method_name] = [];

                            foreach ($method_parameters as $field_name => $field_value) {
                                if (!isset($combined[$name][$method_name][$field_name]))
                                    $combined[$name][$method_name][$field_name] = [];

                                switch ($field_name) {
                                    case 'table_columns':
                                        foreach ($field_value as $column) {
                                            if (!in_array($column, $combined[$name][$method_name][$field_name])) {
                                                $combined[$name][$method_name][$field_name][] = $column;
                                            }
                                        }
                                        break;
                                    default:
                                        $this->arrayRecursion($combined[$name][$method_name][$field_name], $field_name, $field_value);
                                }
                            }
                        }
                        break;
                    case 'conditions':
                        foreach ($modifier as $condition)
                            $combined[$name][] = $condition;
                        break;
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
     * Check LCM condition
     *
     * @throws UnknownConditionOperatorException
     *
     * @param $cond_value
     * @param $operator
     * @param $value
     *
     * @return bool
     */
    private function isNotCheck($cond_value, $operator, $value)
    {
        if ($operator == 'is') {
            if (is_array($value))
                return in_array($cond_value, $value);
            else
                return $cond_value == $value;
        } else if ($operator == 'not')
            if (is_array($value))
                return !in_array($cond_value, $value);
            else
                return $cond_value != $value;
        else
            throw new UnknownConditionOperatorException();
    }

    /**
     * Get relations for combined parameters
     *
     * @param stdClass $params
     * @param stdClass|array &$relations
     * @return stdClass
     */
    public function getRelations(stdClass $params, &$relations = [])
    {
        // if array, cast to object (one time action)
        $relations = $this->arrayToObject($relations);

        if (!isset($relations->users))
            $relations->users = User::all(['id', 'name', 'surname']);
        if (!isset($relations->statuses))
            $relations->statuses = LeafStatus::all();

        foreach ($params as $field_name => $field_modifiers) {
            if (!isset($field_modifiers->type)) {
                $relations = $this->getRelations((object)$field_modifiers->fields, $relations);
            } else if ($field_modifiers->type == 'relation') {
                // get id of leaf type
                // todo notify if none id found
                $leaf_type_id = LeafType::where('slug', Str::plural($field_modifiers->leaf_type))->value('id');

                // if relation already loaded, continue
                if (!isset($relations->$field_name))
                    $relations->$field_name = Leaf::with(['LCMV', 'leaf_type', 'user', 'status'])
                        ->where('leaf_type_id', $leaf_type_id)->get();
            }
        }

        return $relations;
    }

    /**
     * Add values from model's LCMV
     *
     * @throws AssigningNullToNotNullableException
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     * @throws UnknownRelationException
     *
     * @param &$model
     * @param int|string|LeafType $type
     * @param stdClass|null $params
     * @param $val
     *
     * @return mixed
     */
    public function populateWithLCMV($model, $type, stdClass $params = null, $val = null)
    {
        // Get instance of LeafType
        $leaf_type = $this->getLeafType($type);

        /**
         * Combine all LeafType's modifiers.
         *
         * If two modifiers have same fields, only first one will be used.
         * Additionally, log message and notification will be made.
         */
        $params = is_null($params) ? $this->combineLCMs($leaf_type)->lcm : $params;

        if (is_null($val) && (isset($model->LCMV->values)))
            $val = $model->LCMV->values;

        /**
         * Assign values to prepared parameters.
         */
        foreach ($params as $field_name => $field_modifiers) {
            if (!isset($field_modifiers->type)) {
                foreach ($field_modifiers->fields as $entity_name => $entity_modifier) {
                    $temp = isset($model->$field_name) ? $model->$field_name : new stdClass();
                    $model->$field_name = $this->populateWithLCMV($temp, $leaf_type, $field_modifiers->fields, $val->$field_name ?? null);
                }
            } else {
                /**
                 * If LCMV have value for parameter, assign it.
                 */
                if (isset($val->$field_name) && !empty($val->$field_name)) {
                    $model->$field_name = $val->$field_name;

                    /**
                     * Load needed relations
                     */
                    if ($field_modifiers->type == 'relation')
                        $this->getSingleRelation($model, $field_name, $field_modifiers);
                } else {
                    // assign default value, if it is set
                    if (isset($field_modifiers->default)) {
                        $model->$field_name = $field_modifiers->default;

                        if ($field_modifiers->type == 'relation')
                            $this->getSingleRelation($model, $field_name, $field_modifiers, true);
                    } // if default value is not set, assign null if possible
                    else if (isset($field_modifiers->nullable) && $field_modifiers->nullable == true)
                        $model->$field_name = null;
                    // if default value is not set and parameter is not nullable, throw exception
                    else
                        throw new AssigningNullToNotNullableException($field_name);
                }
            }
        }

        return $model;
    }

    /**
     * Get relation for param field
     *
     * @throws AssigningNullToNotNullableException
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     * @throws UnknownRelationException
     *
     * @param BaseModel &$model
     * @param $field_name
     * @param $field_modifiers
     * @param bool $useDefault
     */
    public function getSingleRelation(BaseModel &$model, $field_name, $field_modifiers, bool $useDefault = false)
    {
        switch ($field_modifiers->relation_type) {
            case 'belongsTo':
                // Get leaf id
                $id = $useDefault
                    ? $field_modifiers->default
                    : $model->LCMV->values->$field_name;

                // Get leaf instance
                $leaf = Leaf::with(['LCMV', 'status', 'user', 'leaf_type'])->findOrFail($id);
                $leaf = $this->populateWithLCMV($leaf, $leaf->leaf_type);

                // Push relation leaf to model
                $model->$field_name = $leaf;
                break;

            case 'belongsToMany':
                // Get leaf id
                $ids = $useDefault
                    ? $field_modifiers->default
                    : $model->LCMV->values->$field_name;

                // Get leaf instance
                $leafs = Leaf::with(['LCMV', 'status', 'user', 'leaf_type'])->findMany($ids);
                foreach ($leafs as $leaf)
                    $leaf = $this->populateWithLCMV($leaf, $leaf->leaf_type);

                // Push relation leaf to model
                $model->$field_name = $leafs;
                break;

            default:
                throw new UnknownRelationException($field_name);
        }
    }

    /**
     * Get menu items
     *
     * @throws AssigningNullToNotNullableException
     * @throws UnknownConditionOperatorException
     * @throws UnknownConditionParameterException
     * @throws UnknownRelationException
     *
     * @return Collection
     */
    public function getMenuItems()
    {
        $leaf_type = LeafType::with('LCMs')->where('slug', 'admin-menu-items')->first();
        $leaves = Leaf::where('leaf_type_id', $leaf_type->id)->get();
        $page_type = explode('/', Route::getCurrentRoute()->uri)[1] ?? '';

        foreach ($leaves as &$leaf) {
            $leaf = $this->populateWithLCMV($leaf, $leaf_type);
        }

        // Separate sections (with parent_id = null)
        list($sections, $leaves) = $leaves->partition(function ($item) {
            return $item->parent == null;
        });

        foreach ($sections as &$section) {
            $section->setAttribute('is_current', $section->slug == $page_type);

            // Get items for each section
            list($section->children, $leaves) = $leaves->partition(function ($item) use (&$section, $page_type) {
                if ($item->parent->id == $section->id) {
                    if (!$section->is_current)
                        $section->setAttribute('is_current', $item->slug == $page_type);
                    $item->setAttribute('is_current', $item->slug == $page_type);
                    return true;
                } else
                    return false;
            });
        }

        return $sections;
    }

    /**
     * Convert array to object recursively
     *
     * @param array $array Array to cast
     * @return stdClass|array Object casted from array or array value of parameter
     */
    function arrayToObject($array)
    {
        if (!is_array($array)) return $array;
        foreach ($array as &$item) {
            $item = $this->arrayToObject($item);
        }
        return (object)$array;
    }

    /**
     * Deploy Alder routes
     */
    public function routes()
    {
        require __DIR__ . '/../routes/alder.php';
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
    public function returnResponse(bool $isAJAX, string $message, bool $success = true, string $alert_type = 'primary', string $exception = null)
    {
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
    public function returnRedirect(bool $isAJAX, string $message, string $redirectTo, bool $success = true, string $alert_type = 'primary', string $exception = null)
    {
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


    public function chooseNameFormRptr($field_name, $values)
    {

        if (isset($values->$field_name))
            return $field_name;
        else {
            preg_match("/\d$/", $field_name, $match);

            if (count($match) > 0) :
                $field_name = str_replace($match[0], ++$match[0], $field_name);
                return $this->chooseNameFormRptr($field_name, $values);
            else :
                $field_name = $field_name . "_1";
                return $this->chooseNameFormRptr($field_name, $values);
            endif;
        }
    }
}


