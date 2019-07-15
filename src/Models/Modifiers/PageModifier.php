<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class PageModifier extends BaseModifier
{
    public const leaf_type = 'page';
    
    public const structure = [
        'fields' => [
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
        ],
        'relations' => [
            'parent' => [
                'type' => 'relation',
                'relation_type' => 'belongsTo',
                'leaf_type' => 'page',
                'nullable' => true,
            ],
        ],
    ];
}
