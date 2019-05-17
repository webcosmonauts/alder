<?php
    
namespace Webcosmonauts\Alder\Models;

use Illuminate\Database\Eloquent\Model;
use Webcosmonauts\Alder\Models\Traits\BelongsToLeafTypeTrait;

class AdminMenuItem extends BaseModel
{
    use BelongsToLeafTypeTrait;
    
    public function parent() {
        return $this->belongsTo(AdminMenuItem::class, 'parent_id')->with('parent');
    }
    
    public function children() {
        return $this->hasMany(AdminMenuItem::class, 'parent_id')->with('children');
    }
}
