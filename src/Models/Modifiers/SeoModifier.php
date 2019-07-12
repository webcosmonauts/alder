<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class SeoModifier extends BaseModifier
{
    public $leaf_type = ['page', 'post', 'report'];
    
    public $fields = [
        'seo_description' => [
            'type' => 'text',
            'nullable' => true,
        ],
        'seo_keywords' => [
            'type' => 'text',
            'nullable' => true,
        ],
    ];
}
