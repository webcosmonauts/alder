<?php
    
namespace Webcosmonauts\Alder\Models\Translations;

use Webcosmonauts\Alder\Models\BaseModel;

class RootTranslation extends BaseModel
{
    public $timestamps = false;
    public $fillable = ['title'];
}
