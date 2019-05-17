<?php

namespace Webcosmonauts\Alder\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webcosmonauts\Alder\Models\Traits\BelongsToLeafTypeTrait;
use Webcosmonauts\Alder\Models\Traits\TableColumnsTrait;

class User extends Authenticatable
{
    use Notifiable, 
        BelongsToLeafTypeTrait,
        TableColumnsTrait;
    
    protected $table_columns = ['full_name'];
    
    public function getFullNameAttribute() {
        return $this->name . ' ' . $this->surname;
    }
}
