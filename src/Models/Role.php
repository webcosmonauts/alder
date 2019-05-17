<?php
    
namespace Webcosmonauts\Alder\Models;

use Illuminate\Database\Eloquent\Model;
use Webcosmonauts\Alder\Models\Traits\BelongsToLeafTypeTrait;

class Role extends Model
{
    use BelongsToLeafTypeTrait;
}
