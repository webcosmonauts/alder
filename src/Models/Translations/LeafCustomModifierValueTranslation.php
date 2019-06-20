<?php
    
namespace Webcosmonauts\Alder\Models\Translations;

use Webcosmonauts\Alder\Models\BaseModel;

class LeafCustomModifierValueTranslation extends BaseModel
{
    protected $table = 'lcmv_translations';
    public $timestamps = false;
    public $fillable = ['values'];
    
    protected $casts = [
        'values' => 'object'
    ];
}
