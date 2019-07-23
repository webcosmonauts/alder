<?php
namespace Webcosmonauts\Alder\Models;

use Carbon\Carbon;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Models\Widgets\FooterWidget;
use Webcosmonauts\Alder\PackageModifiersAccessor;

/**
 * Class Leaf
 * @package Webcosmonauts\Alder\Models
 * @mixin \Illuminate\Database\Eloquent\Builder
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
 * @method static \Illuminate\Database\Eloquent\Builder leafType(string $string)
 *
 * @property User user
 */
class Leaf extends BaseModel
{
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::addGlobalScope('with_modifiers', function () {
        
        });
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function scopeLeafType(Builder $query, string $leaf_type) {
        return $query->where('leaf_type', $leaf_type);
    }
    
    public function getCreatedAtAttribute($value) {
        $date = Root::where('slug', 'date-format')->value('value');
        $time = Root::where('slug', 'time-format')->value('value');
        $format_string = ($date ?? 'Y-m-d') . ' ' . ($time ?? 'H:i:s');
        return Carbon::parse($value)->format($format_string);
    }
    
    public function getUpdatedAtAttribute($value) {
        $date = Root::where('slug', 'date-format')->value('value');
        $time = Root::where('slug', 'time-format')->value('value');
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
        $return = parent::getRelationValue($key);
        
        /**
         * If Eloquent relation is found, return it.
         */
        if (!is_null($return))
            return $return;
    
        /**
         * Else, try to get modifier from registered modifiers.
         */
        if (Alder::hasPackage($key))
            return new PackageModifiersAccessor($this, $key);
        
        return null;
    }
    
    /**
     * Save the model to the database.
     *
     * @param array $options
     * @param bool $with_modifiers
     * @return bool
     */
    public function save(array $options = [], bool $with_modifiers = true)
    {
        /**
         * If parent save method fails - abort.
         */
        if (!parent::save($options))
            return false;
    
        /**
         * If base model saving is complete and we do not need
         * to save model's modifiers - return true. Otherwise,
         * perform modifiers saving.
         */
        if (!$with_modifiers)
            return false;
        
        // TODO: save modifiers
    }
}
