<?php
    
namespace Webcosmonauts\Alder\Models;

class Leaf extends BaseModel
{
    public function LCMV() {
        return $this->hasOne(LeafCustomModifierValue::class, 'id', 'LCMV_id');
    }
}
