<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class SeoModifier extends BaseModifier
{
    public const leaf_type = ['page', 'post', 'report'];
    
    public const structure = [
        'fields' => [
            'seo_description' => [
                'type' => 'text',
                'nullable' => true,
            ],
            'seo_keywords' => [
                'type' => 'text',
                'nullable' => true,
            ],
        ],
    ];
}
