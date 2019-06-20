<?php

namespace Webcosmonauts\Alder\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class BaseModel extends Model
{
    use HasRoles;

    protected $guard_name = 'AlderGuard';
}
