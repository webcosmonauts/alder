<?php
    
namespace Webcosmonauts\Alder\Models;

/**
 * Class LeafCustomModifierValue
 * @property int id
 * @property string values
 */
class LeafCustomModifierValue extends BaseModel
{
    protected $casts = [
        'values' => 'object'
    ];
}
