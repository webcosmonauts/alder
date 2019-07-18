<?php
    
namespace Webcosmonauts\Alder\Models;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\PackageModifiersAccessor;

/**
 * Class Leaf
 * @package Webcosmonauts\Alder\Models
 *
 * @property int id
 * @property string title
 * @property string slug
 * @property string content
 * @property bool is_accessible
 * @property int user_id
 * @property string leaf_type
 * @property string status
 * @property int revision
 * @property string created_at
 * @property string updated_at
 *
 * @property User user
 */
class Leaf extends BaseModel
{
    use Translatable;
    
    public $translatedAttributes = ['title', 'slug', 'content'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function scopeLeafType(Builder $query, string $leaf_type) {
        return $query->where('leaf_type', $leaf_type);
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

    /**
     * Get a relationship.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getRelationValue($key)
    {
        // If the key already exists in the relationships array, it just means the
        // relationship has already been loaded, so we'll just return it out of
        // here because there is no need to query within the relations twice.
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        // If the "attribute" exists as a method on the model, we will just assume
        // it is a relationship and will load and return results from the query
        // and hydrate the relationship's value on the "relationships" array.
        if (method_exists($this, $key)) {
            return $this->getRelationshipFromMethod($key);
        }

        // Object to access modifiers in package named $key
        if (Alder::hasPackage($key)) {
            return new PackageModifiersAccessor($this, $key);
        }
    }
}
