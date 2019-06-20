<?php

namespace Webcosmonauts\Alder\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    
    public function getFullNameAttribute() {
        return $this->name . ' ' . $this->surname;
    }
    
    public function leaf_type() {
        return $this->belongsTo(LeafType::class);
    }

}
