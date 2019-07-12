<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class AdminMenuItemModifier extends BaseModifier
{
    public $leaf_type = 'admin_menu_item';
    
    public $fields = [
        'icon' => [
            'type' => 'string',
            'default' => 'ellipsis-h',
        ],
        'parent' => [
            'type' => 'relation',
            'relation_type' => 'belongsTo',
            'leaf_type' => 'admin_menu_item',
            'nullable' => true,
        ],
        'urc' => [
            'type' => 'text',
            'nullable' => true,
        ],
    ];
    
    public $bread_fields = [
        'browse' => ['title', 'icon'],
    ];
}
