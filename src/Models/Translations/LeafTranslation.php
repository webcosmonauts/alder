<?php
    
namespace Webcosmonauts\Alder\Models\Translations;

use Webcosmonauts\Alder\Models\BaseModel;

class LeafTranslation extends BaseModel
{
    public $timestamps = false;
    protected $fillable = ['title', 'slug', 'content'];
}
