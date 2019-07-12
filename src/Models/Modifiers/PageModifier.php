<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class PageModifier extends BaseModifier
{
    public $leaf_type = 'page';
    
    public $fields = [
        'parent' => [
            'type' => 'relation',
            'relation_type' => 'belongsTo',
            'leaf_type' => 'page',
            'nullable' => true,
        ],
        'template' => [
            'type' => 'template',
            'nullable' => true,
        ],
        'thumbnail' => [
            'type' => 'file',
            'nullable' => true,
        ],
        'page_bg' => [
            'type' => 'file',
            'nullable' => true,
        ],
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
