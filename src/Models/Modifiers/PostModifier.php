<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class PostModifier extends BaseModifier
{
    public const leaf_type = 'post';
    
    public const structure = [
        'fields' => [
            'thumbnail' => [
                'type' => 'file',
                'nullable' => true,
            ],
        ],
        'relations' => [
            'tags' => [
                'type' => 'belongsToMany',
                'leaf_type' => 'post_tag',
                'nullable' => true,
            ],
            'categories' => [
                'type' => 'belongsToMany',
                'leaf_type' => 'post_category',
                'nullable' => true,
            ],
        ]
    ];
}
