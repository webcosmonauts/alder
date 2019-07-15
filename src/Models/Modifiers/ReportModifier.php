<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class ReportModifier extends BaseModifier
{
    public const leaf_type = 'report';
    
    public const structure = [
        'fields' => [
            'thumbnail' => [
                'type' => 'file',
                'nullable' => true,
            ],
        ],
    ];
}
