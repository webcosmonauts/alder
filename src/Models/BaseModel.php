<?php

namespace Webcosmonauts\Alder\Models;
use Illuminate\Database\Eloquent\Model;
use Webcosmonauts\Alder\Models\Traits\TableColumnsTrait;

class BaseModel extends Model
{
    use TableColumnsTrait;
    
    protected $table_columns = ['title', 'created_at', 'updated_at'];
    
    protected $casts = [
        'created_at' => 'datetime:H:i:s d.m.Y',
        'updated_at' => 'datetime:H:i:s d.m.Y',
    ];
}