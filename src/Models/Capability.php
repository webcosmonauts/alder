<?php
    
namespace Webcosmonauts\Alder\Models;

use Webcosmonauts\Alder\Models\Traits\BelongsToLeafTypeTrait;
use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    use BelongsToLeafTypeTrait;
}
