<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

class ContactPageModifier extends BaseModifier
{
    public $leaf_type = 'page';
    
    public $fields = [
        'contact_form' => [
            'type' => 'relation',
            'relation_type' => 'belongsTo',
            'leaf_type' => 'contact_form',
            'nullable' => true,
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
