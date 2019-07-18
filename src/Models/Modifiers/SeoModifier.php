<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class SeoModifier extends BaseModifier
{
    public const leaf_type = ['page', 'post', 'report'];
    
    public const structure = [
        'fields' => [
            'description' => [
                'type' => 'text',
                'nullable' => true,
            ],
            'keywords' => [
                'type' => 'text',
                'nullable' => true,
            ],
        ],
    ];
}
