<?php
    
namespace Webcosmonauts\Alder\Models;

class LeafType extends BaseModel
{
    public function LCMs() {
        return $this->hasMany(LeafCustomModifier::class, 'id', 'LCM_id');
    }
}
