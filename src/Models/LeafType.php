<?php
    
namespace Webcosmonauts\Alder\Models;

use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string title
 * @property string slug
 * @property int LCM_id
 * @property string created_at
 * @property string updated_at
 *
 * @property Collection LCMs
 */
class LeafType extends BaseModel
{
    public function LCMs() {
        return $this->hasMany(LeafCustomModifier::class, 'id', 'LCM_id');
    }
}
