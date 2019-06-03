<?php
namespace Webcosmonauts\Alder;

use http\Exception\InvalidArgumentException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Webcosmonauts\Alder\Exceptions\AssigningNullToNotNullableException;
use Webcosmonauts\Alder\Exceptions\ModifierTypeNotSupportedException;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\LeafType;
use Webcosmonauts\Alder\Models\BaseModel;

class Alder
{
    /**
     * Get LeafType based on $type data type.
     *
     * If $type is an instance of LeafType - use it, else
     * get instance from database using $type as id or name,
     * depending on its data type.
     * If its neitgher of those, the invalid argument exception will be thrown.
     *
     * @param int|string $param
     * @return LeafType
     */
    public function getLeafType($param) {
        if (is_int($param))
            return $leaf_type = LeafType::with('LCMs')->find($param);
        else if (is_string($param))
            return $leaf_type = LeafType::with('LCMs')->where('name', $param)->first();
        else
            throw new InvalidArgumentException();
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
     * Add values from model's LCMV
     *
     * @param BaseModel $model
     * @param int|string|LeafType $type
     * @param object $combined
     */
    public function populateWithLCMV(BaseModel &$model, $type, $combined = null) {
        // Get instance of LeafType
        $leaf_type = $type instanceof LeafType ? $type : $this->getLeafType($type);
        
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
            if (isset($model->LCMV->values->$field_name))
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
                    throw new AssigningNullToNotNullableException();
            }
        }
    }
    
    /**
     * Get menu items
     *
     * @return Collection
     */
    public function getMenuItems() {
        $leaf_type = LeafType::with('LCMs')->where('name', 'admin-menu-items')->first();
        $leaves = Leaf::where('leaf_type_id', $leaf_type->id)->get();
        $page_type = explode('/', Route::getCurrentRoute()->uri)[1];
        
        foreach ($leaves as &$leaf) {
            $this->populateWithLCMV($leaf, $leaf_type);
        }
        
        // Separate sections (with parent_id = null)
        list($sections, $leaves) = $leaves->partition(function ($item) {
            return $item->parent_id == null;
        });
        
        foreach ($sections as &$section) {
            if ($section->slug == $page_type)
                $section->setAttribute('is_current', true);
            
            // Get items for each section
            list($section->children, $leaves) = $leaves->partition(function ($item) use (&$section, $page_type) {
                if ($item->slug == $page_type) {
                    $section->setAttribute('is_current', true);
                    $item->setAttribute('is_current', true);
                }
                
                return $item->parent_id == $section->id;
            });
            
            // Get children for each item
            foreach ($section->children as &$menu_item) {
                list($menu_item->children, $leaves) = $leaves->partition(function ($item) use (&$section, &$menu_item, $page_type) {
                    if ($item->slug == $page_type) {
                        $section->setAttribute('is_current', true);
                        $menu_item->setAttribute('is_current', true);
                        $item->setAttribute('is_current', true);
                    }
                    return $item->parent_id == $menu_item->id;
                });
            }
        }
        
        return $sections;
    }
    
    /**
     * Convert array to object recursively
     *
     * @param array $array Array to cast
     * @return \stdClass Object casted from array
     */
    function arrayToObject(array $array) {
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
}
