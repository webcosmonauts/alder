<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class BaseModifier
{
    public $leaf_type = '';
    
    // Package prefix for columns in database
    public $prefix = 'alder';
    
    // Modifier fields
    public $fields = [
        'browse' => 'title'
    ];
    
    // Fields to show in browse view
    public $bread_fields = [];
    
    // Conditions on which to use modifier fields
    public $conditions = [];
}