<?php

namespace Webcosmonauts\Alder\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    const ADMIN_TYPE = 'admin';
    const DEFAULT_TYPE = 'user';
    
    public function isAdmin(){
        return $this->type === self::ADMIN_TYPE;
    }
    
    public function getFullNameAttribute() {
        return $this->name . ' ' . $this->surname;
    }
    
    public function leaf_type() {
        return $this->belongsTo(LeafType::class);
    }
    
    public function getCreatedAtAttribute($value) {
        $date = Root::whereSlug('date-format')->value('value');
        $time = Root::whereSlug('time-format')->value('value');
        $format_string = ($date ?? 'Y-m-d') . ' ' . ($time ?? 'H:i:s');
        return Carbon::parse($value)->format($format_string);
    }
    
    public function getUpdatedAtAttribute($value) {
        $date = Root::whereSlug('date-format')->value('value');
        $time = Root::whereSlug('time-format')->value('value');
        $format_string = ($date ?? 'Y-m-d') . ' ' . ($time ?? 'H:i:s');
        return Carbon::parse($value)->format($format_string);
    }
}
