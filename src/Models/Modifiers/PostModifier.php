<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class PostModifier extends BaseModifier
{
    public $leaf_type = 'post';
    
    public $fields = [
        'tags' => [
            'type' => 'relation',
            'relation_type' => 'belongsToMany',
            'leaf_type' => 'post_tag',
            'nullable' => true,
        ],
        'categories' => [
            'type' => 'relation',
            'relation_type' => 'belongsToMany',
            'leaf_type' => 'post_category',
            'nullable' => true,
        ],
        'thumbnail' => [
            'type' => 'file',
            'nullable' => true,
        ],
    ];
}
