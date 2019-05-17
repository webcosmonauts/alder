<?php

namespace Webcosmonauts\Alder\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webcosmonauts\Alder\Models\Traits\BelongsToLeafTypeTrait;

class User extends Authenticatable
{
    use Notifiable, BelongsToLeafTypeTrait;
    
    protected $table_columns = ['full_name'];
    
    public function getTableColumnsAttribute() {
        return $this->table_columns;
    }
    
    public function getFullNameAttribute() {
        return $this->name . ' ' . $this->surname;
    }
}
