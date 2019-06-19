<?php
    
namespace Webcosmonauts\Alder\Models\Translations;

use Webcosmonauts\Alder\Models\BaseModel;

class LeafCustomModifierTranslation extends BaseModel
{
    protected $table = 'lcm_translations';
    public $timestamps = false;
    public $fillable = ['title', 'group_title'];
}
