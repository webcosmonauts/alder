<?php
    
namespace Webcosmonauts\Alder\Models;

use Illuminate\Database\Eloquent\Model;
use Webcosmonauts\Alder\Models\Traits\BelongsToLeafTypeTrait;

class Setting extends Model
{
    public function tab() {
        return $this->belongsTo(SettingTab::class, 'tab_id');
    }
    
    use BelongsToLeafTypeTrait;
}
