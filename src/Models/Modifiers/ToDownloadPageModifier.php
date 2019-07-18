<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class ToDownloadPageModifier extends BaseModifier
{
    public const leaf_type = 'page';
    
    public const structure = [
        'fields' => [
            'section_title' => [
                'type' => 'text',
                'nullable' => true,
            ],
            'left_img' => [
                'type' => 'file',
                'nullable' => true
            ],
            'repeater' => [
                'type' => 'repeater',
                'nullable' => true,
                'fields' => [
                    'name' => [
                        'type' => 'text',
                        'nullable' => true
                    ],
                    'files' => [
                        'type' => 'repeater',
                        'nullable' => true,
                        'fields' => [
                            'file' => [
                                'type' => 'file',
                                'nullable' => true
                            ],
                            'comment' => [
                                'type' => 'text',
                                'nullable' => true
                            ]
                        ]
                    ]
                ]
            ],
        ],
    ];
    
    public $conditions = [
        [
            'parameter' => 'page_template',
            'operator' => 'is',
            'value' => 'to_download',
        ]
    ];
}
