<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class ContactPageModifier extends BaseModifier
{
    public const leaf_type = 'page';
    
    public const structure = [
        'relations' => [
            'contact_form' => [
                'type' => 'relation',
                'relation_type' => 'belongsTo',
                'leaf_type' => 'contact_form',
                'nullable' => true,
            ],
        ],
    ];
    
    public $conditions = [
        [
            'parameter' => 'page_template',
            'operator' => 'is',
            'value' => 'contact',
        ],
    ];
}
