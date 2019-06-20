<?php
    
namespace Webcosmonauts\Alder\Models;

/**
 * @property int id
 * @property string title
 * @property string slug
 * @property string created_at
 * @property string updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection\Collection roots
 */
class RootType extends BaseModel
{
    public function roots() {
        return $this->hasMany(Root::class);
    }
}
