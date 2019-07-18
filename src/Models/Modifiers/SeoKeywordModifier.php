<?php


namespace Webcosmonauts\Alder\Models\Modifiers;


use Webcosmonauts\Alder\Models\Leaf;

class SeoKeywordModifier extends BaseModifier
{
    public const leaf_type = 'seo_keyword';

    public const structure = [
        'fields' => [
            'keyword' => [
                'type' => 'string',
                'nullable' => true,
            ],
        ],
        'relations' => [
            'keyword__post' => [
                'type' => 'belongsToMany',
                'leaf_type' => 'post',
            ],
        ]
    ];

    public function posts() {
        return $this->belongsToMany(
            Leaf::class,
            'alder__mtm__keyword__post',
            'source_id',
            'target_id',
            'id',
            'id'
        );
    }
}
