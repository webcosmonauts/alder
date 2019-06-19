<?php
    
namespace Webcosmonauts\Alder\Models;

use Dimsav\Translatable\Translatable;

/**
 * Class LeafCustomModifierValue
 * @property int id
 * @property string values
 */
class LeafCustomModifierValue extends BaseModel
{
    use Translatable;
    
    protected $table = 'lcmvs';
    public $translatedAttributes = ['values'];
    
    protected $casts = [
        'values' => 'object'
    ];
}
