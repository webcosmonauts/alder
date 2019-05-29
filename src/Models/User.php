<?php

namespace Webcosmonauts\Alder\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    public function getFullNameAttribute() {
        return $this->name . ' ' . $this->surname;
    }
    
    public function leaf_type() {
        return $this->belongsTo(LeafType::class);
    }
}
