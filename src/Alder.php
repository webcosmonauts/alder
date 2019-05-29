<?php
namespace Webcosmonauts\Alder;

use http\Exception\InvalidArgumentException;
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
        if (is_int($type))
            return $leaf_type = LeafType::with('LCMs')->find($type);
        else if (is_string($type))
            return $leaf_type = LeafType::with('LCMs')->where('name', $type)->first();
        else
            throw new InvalidArgumentException();
    }
    
    /**
     * Add values from model's LCMV
     *
     * @param BaseModel $model
     * @param int|string|LeafType $type
     */
    public function populateWithLCMV(BaseModel &$model, $type) {
        // Get instance of LeafType
        if ($type instanceof LeafType) {
            $leaf_type = $type;
            // Load LCMs if they were not loaded
            $leaf_type->loadMissing('LCMs');
        }
        else
            $leaf_type = $this->getLeafType($type);
        
        /**
         * Prepare array of fields from all modifiers.
         *
         * If two modifiers have same fields, only first one will be used.
         * Additionally, log message and notification will be made.
         */
        $params = [];
        foreach ($leaf_type->LCMs as $LCM) {
            foreach ($LCM->modifiers->modifiers as $field_name => $modifier) {
                if (!isset($params[$field_name]))
                    $params[$field_name] = $modifier;
                else {
                    logger('Modifier ' . $LCM->name . ' has field '
                        . $field_name . ' that already exists in another modifier.');
                    // TODO add notification
                }
            }
        }
        
        /**
         * Assign values to prepared parameters.
         */
        foreach (array_keys($params) as $field_name) {
            // If LCMV have value for parameter, assign it.
            if (isset($model->LCMV->values->$field_name))
                $model->$field_name = $model->LCMV->values->$field_name;
            else {
                // assign default value, if it is set
                if (isset($params[$field_name]->default))
                    $model->$field_name = $params[$field_name]->default;
                // if default value is not set, assign null if possible
                else if (isset($params[$field_name]->nullable) && $params[$field_name]->nullable == true)
                    $model->$field_name = null;
                // if default value is not set and parameter is not nullable, throw exception
                else
                    throw new AssigningNullToNotNullableException();
            }
        }
    }
    
    /**
     *
     */
    public function getMenuItems() {
        $menu_items = [];
        $leaf_type = LeafType::with('LCMs')->where('name', 'admin-menu-items')->first();
        $leaves = Leaf::where('leaf_type_id', $leaf_type->id)->get();
    
        foreach ($leaves as $leaf) {
            $this->populateWithLCMV($leaf, $leaf_type);
        }
        
        return $leaves;
    }
}
