<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class TestModifier extends BaseModifier
{
    public const leaf_type = 'test';
    
    public const structure = [
        'fields' => [
            'icon' => [
                'type' => 'string',
                'default' => 'ellipsis-h',
            ],
            'text_test' => [
                'type' => 'text',
            ],
            'file_test' => [
                'type' => 'file',
                'nullable' => true,
            ],
            'file_multiple_test' => [
                'type' => 'file-multiple',
                'nullable' => true,
            ],
            'slider' => [
                'type' => 'repeater',
                'fields' => [
                    'slide_text' => [
                        'type' => 'text',
                        'default' => 'ellipsis-h',
                    ],
                    'slide_bg' => [
                        'type' => 'file',
                        'nullable' => true,
                    ],
                    'slide_options' => [
                        'type' => 'radio',
                        'options' => ['only_text', 'only_image', 'both'],
                        'nullable' => true,
                    ],
                ],
            ],
            'select_opt' => [
                'type' => 'select',
                'options' => ['only_text', 'only_image', 'both'],
                'nullable' => true,
            ],
            'select_multiple_options' => [
                'type' => 'select-multiple',
                'options' => ['only_text', 'only_image', 'both'],
                'nullable' => true,
            ],
            'password' => [
                'type' => 'password',
                'nullable' => true,
            ],
            'date' => [
                'type' => 'date',
                'nullable' => true,
            ],
            'datetime-local' => [
                'type' => 'datetime-local',
                'nullable' => true,
            ],
            'time' => [
                'type' => 'time',
                'nullable' => true,
            ],
            'month' => [
                'type' => 'month',
                'nullable' => true,
            ],
            'color' => [
                'type' => 'color',
                'nullable' => true,
            ],
            'text_img' => [
                'type' => 'image',
                'default' => 'ellipsis-h',
                'nullable' => true,
            ],
            'order' => [
                'type' => 'number',
                'nullable' => true,
            ],
            'is_active' => [
                'type' => 'checkbox',
                'default' => true,
            ],
        ],
        'relations' => [
            'test_relation_many' => [
                'type' => 'relation',
                'relation_type' => 'belongsToMany',
                'leaf_type' => 'admin_menu_item',
                'nullable' => true,
            ],
            'test_relation' => [
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
