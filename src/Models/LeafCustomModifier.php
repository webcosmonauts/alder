<?php
    
namespace Webcosmonauts\Alder\Models;

/**
 * Class LeafCustomModifier
 *
 * @property int id
 * @property string title
 * @property string slug
 * @property object modifiers
 */
class LeafCustomModifier extends BaseModel
{
    protected $casts = [
        'modifiers' => 'object'
    ];
    
    public function leaf_type() {
        return $this->belongsTo(LeafType::class);
    }
}
