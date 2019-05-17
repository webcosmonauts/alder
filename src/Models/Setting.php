<?php
    
namespace Webcosmonauts\Alder\Models;

use Webcosmonauts\Alder\Models\Traits\BelongsToLeafTypeTrait;

class Setting extends BaseModel
{
    use BelongsToLeafTypeTrait;
    
    public function tab() {
        return $this->belongsTo(SettingTab::class, 'tab_id');
    }
}
