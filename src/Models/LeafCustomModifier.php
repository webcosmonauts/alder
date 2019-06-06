<?php
    
namespace Webcosmonauts\Alder\Models;

/**
 * Class LeafCustomModifier
 *
 * @property int id
 * @property string name
 * @property object modifiers
 */
class LeafCustomModifier extends BaseModel
{
    protected $casts = [
        'modifiers' => 'object'
    ];
}
