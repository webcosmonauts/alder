<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

use Illuminate\Support\Str;
use Webcosmonauts\Alder\Models\BaseModel;

abstract class BaseModifier extends BaseModel
{
    public const leaf_type = '';
    
    // Package prefix for columns in database
    public const prefix = 'alder';
    
    // Modifier fields
    public const structure = [
        'fields'    => [],
        'relations' => [],
        'indexes'   => [],
    ];
    
    // Fields to show in browse view
    public $bread_fields = [];

    // Conditions on which to use modifier fields
    public $conditions = [];

    public function getTable() {
        return static::prefix ."__". ($this->table ?? Str::snake(Str::pluralStudly(class_basename($this))));
    }
}
