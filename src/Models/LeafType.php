<?php
    
namespace Webcosmonauts\Alder\Models;

/**
 * @property int id
 * @property string slug
 * @property bool is_accessible
 * @property bool is_singular
 * @property string created_at
 * @property string updated_at
 *
 * @property \Illuminate\Support\Collection\Collection leaves
 * @property \Illuminate\Support\Collection\Collection LCMs
 */
class LeafType extends BaseModel
{
    public function leaves() {
        return $this->hasMany(Leaf::class);
    }
    public function LCMs() {
        return $this->hasMany(LeafCustomModifier::class);
    }
}
