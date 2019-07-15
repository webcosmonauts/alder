<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class AdminMenuItemModifier extends BaseModifier
{
    public const leaf_type = 'admin_menu_item';
    
    public const structure = [
        'fields' => [
            'icon' => [
                'type' => 'string',
                'default' => 'ellipsis-h',
            ],
            'urc' => [
                'type' => 'text',
                'nullable' => true,
            ],
        ],
        'relations' => [
            'parent' => [
                'type' => 'relation',
                'relation_type' => 'belongsTo',
                'leaf_type' => 'admin_menu_item',
                'nullable' => true,
            ],
        ],
    ];
    
    public $bread_fields = [
        'browse' => ['title', 'icon'],
    ];
}
