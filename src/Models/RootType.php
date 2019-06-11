<?php
    
namespace Webcosmonauts\Alder\Models;

use Illuminate\Database\Eloquent\Collection;

/**
 * @property int id
 * @property string title
 * @property string slug
 * @property string created_at
 * @property string updated_at
 *
 * @property Collection roots
 */
class RootType extends BaseModel
{
    public function roots() {
        return $this->hasMany(Root::class);
    }
}
