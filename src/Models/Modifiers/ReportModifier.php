<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class ReportModifier extends BaseModifier
{
    public $leaf_type = 'report';
    
    public $fields = [
        'thumbnail' => [
            'type' => 'file',
            'nullable' => true,
        ],
    ];
}
