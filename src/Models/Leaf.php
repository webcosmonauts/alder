<?php
    
namespace Webcosmonauts\Alder\Models;

/**
 * Class Leaf
 * @property int id
 * @property string title
 * @property string slug
 * @property string content
 * @property int user_id
 * @property int leaf_type_id
 * @property int status_id
 * @property int LCMV_id
 * @property int revision
 * @property string created_at
 * @property string updated_at
 *
 * @property LeafType leaf_type
 * @property User user
 * @property LeafCustomModifierValue LCMV
 * @property LeafStatus status
 */
class Leaf extends BaseModel
{
    public function LCMV() {
        return $this->hasOne(LeafCustomModifierValue::class, 'id', 'LCMV_id');
    }
    
    public function leaf_type() {
        return $this->belongsTo(LeafType::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function status() {
        return $this->belongsTo(LeafStatus::class);
    }
}
