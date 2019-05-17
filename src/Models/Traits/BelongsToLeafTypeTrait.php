<?php
    
namespace Webcosmonauts\Alder\Models\Traits;

use Webcosmonauts\Alder\Models\LeafType;

trait BelongsToLeafTypeTrait
{
    public function leaf_type() {
        return $this->belongsTo(LeafType::class);
    }
}