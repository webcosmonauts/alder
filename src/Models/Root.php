<?php
    
namespace Webcosmonauts\Alder\Models;

/**
 * @property int id
 * @property string name
 * @property string value
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
    public function root_type() {
        return $this->belongsTo(RootType::class);
    }
}
