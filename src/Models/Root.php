<?php
    
namespace Webcosmonauts\Alder\Models;

/**
 * @property int id
 * @property string title
 * @property string slug
 * @property string input_type
 * @property string|null value
 * @property string|null options
 * @property int|null order
 * @property string|null capabilities
 * @property bool is_active
 * @property int root_type_id
 * @property string created_at
 * @property string updated_at
 *
 * @property RootType root_type
 */
class Root extends BaseModel
{
    public $casts = [
        'value' => 'array',
        'options' => 'array',
    ];
    
    public function root_type() {
        return $this->belongsTo(RootType::class);
    }
}
