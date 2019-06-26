<?php
    
namespace Webcosmonauts\Alder\Models\Translations;

use Webcosmonauts\Alder\Models\BaseModel;

class NotificationTranslation extends BaseModel
{
    public $timestamps = false;
    protected $fillable = ['title', 'message'];
}
