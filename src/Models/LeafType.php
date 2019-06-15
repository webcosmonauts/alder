<?php
    
namespace Webcosmonauts\Alder\Models;

use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string title
 * @property string slug
 * @property bool is_accessible
 * @property string created_at
 * @property string updated_at
 *
 * @property Collection leaves
 * @property Collection LCMs
 */
class LeafType extends BaseModel
{
    public function leaves(){
        return $this->hasMany(Leaf::class);
    }
    public function LCMs() {
        return $this->hasMany(LeafCustomModifier::class);
    }
}
