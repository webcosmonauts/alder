<?php
    
namespace Webcosmonauts\Alder\Models;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;

/**
 * Class Leaf
 * @property int id
 * @property string title
 * @property string slug
 * @property string content
 * @property bool is_accessible
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
    use Translatable;
    
    public $translatedAttributes = ['title', 'slug', 'content'];
    
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
    
    public function getCreatedAtAttribute($value) {
        $date = Root::whereSlug('date-format')->value('value');
        $time = Root::whereSlug('time-format')->value('value');
        $format_string = ($date ?? 'Y-m-d') . ' ' . ($time ?? 'H:i:s');
        return Carbon::parse($value)->format($format_string);
    }
    
    public function getUpdatedAtAttribute($value) {
        $date = Root::whereSlug('date-format')->value('value');
        $time = Root::whereSlug('time-format')->value('value');
        $format_string = ($date ?? 'Y-m-d') . ' ' . ($time ?? 'H:i:s');
        return Carbon::parse($value)->format($format_string);
    }
    
    public function getUpdatedAtForInputAttribute() {
        return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d\TH:i');
    }
    
    public function setUpdatedAtForInputAttribute($value) {
        $this->attributes['updated_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
