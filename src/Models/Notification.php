<?php
    
namespace Webcosmonauts\Alder\Models;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;

/**
 * Class Leaf
 * @property int id
 * @property string title
 * @property string message
 * @property string alert_type
 * @property string icon
 * @property bool is_read
 * @property int user_id
 * @property string created_at
 * @property string updated_at
 *
 * @property \Webcosmonauts\Alder\Models\User user
 */
class Notification extends BaseModel
{
    use Translatable;
    
    public $translatedAttributes = ['title', 'message'];
    
    public function user() {
        return $this->belongsTo(User::class);
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
}
