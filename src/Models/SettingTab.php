<?php
    
namespace Webcosmonauts\Alder\Models;

use Webcosmonauts\Alder\Models\Traits\BelongsToLeafTypeTrait;

class SettingTab extends BaseModel
{
    use BelongsToLeafTypeTrait;
    
    protected $table_columns = ['title'];
    
    public function settings() {
        return $this->hasMany(Setting::class, 'tab_id');
    }
    
    public function parent() {
        //->where('parent_id',null)
        return $this->belongsTo(SettingTab::class, 'parent_id')->with('parent');
    }
    
    public function children() {
        return $this->hasMany(SettingTab::class, 'parent_id')->with('children');
    }
}
