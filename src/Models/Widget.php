<?php
    
namespace Webcosmonauts\Alder\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;

class Widget extends BaseModel
{
    use Translatable;
    
    public $translatedAttributes = ['title', 'content'];
    
    protected $table = 'leaves';
    
    protected static function boot()
    {
        parent::boot();
        
        self::addGlobalScope('leaf_type', function (Builder $query) {
            $query->where('leaf_type', 'widget');
        });
    }
}
