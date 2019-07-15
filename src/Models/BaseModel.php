<?php

namespace Webcosmonauts\Alder\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Traits\HasRoles;

class BaseModel extends Model
{
    use HasRoles;

    protected $guard_name = 'AlderGuard';
    
    /*public function scopeJoinModifiers(Builder $query, string $leaf_type) {
        $query->join("{$leaf_type}_modifiers", 'id', '=', 'leaf_id');
        return $query;
    }*/
}
