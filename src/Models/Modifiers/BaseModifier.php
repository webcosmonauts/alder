<?php
namespace Webcosmonauts\Alder\Models\Modifiers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Webcosmonauts\Alder\Models\BaseModel;

abstract class BaseModifier extends BaseModel
{
    public const leaf_type = '';
    
    // Package prefix for columns in database
    public const prefix = 'alder';
    
    // Modifier fields
    public const structure = [
        'fields'    => [],
        'relations' => [],
        'indexes'   => [],
    ];

    public static function getTableNameTranslatable() {
        $class = get_called_class();
        return (new $class)->getTable() ."__localized";
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function leaf($id) {
        return static::find($id);
    }

    protected static function boot()
    {
        parent::boot();

//        static::addGlobalScope('translatable', function (Builder $builder) {
//            $builder->leftJoin(static::getTableNameTranslatable(), static::getTableName().'.id', '=', static::getTableNameTranslatable().'.id')->where(static::getTableNameTranslatable().'.lang', 'en');
//        });
    }
    
    // Fields to show in browse view
    public $bread_fields = [];

    // Conditions on which to use modifier fields
    public $conditions = [];

    public function getTable() {
        return $this->table ?? (static::prefix ."__". Str::snake(Str::pluralStudly(class_basename($this))));
    }

    public function scopeTranslate($query, $lang)
    {
        return $query->leftJoin(static::getTableNameTranslatable(), static::getTableName().'.id', '=', static::getTableNameTranslatable().'.id')->where(static::getTableNameTranslatable().'.lang', $lang)->first();
    }

    public function getAttributeValue($key) {
        $value = $this->getAttributeFromArray($key) ?: $this->translate('en')->getAttributeFromArray($key);

        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependant upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
        if ($this->hasCast($key)) {
            return $this->castAttribute($key, $value);
        }

        // If the attribute is listed as a date, we will convert it to a DateTime
        // instance on retrieval, which makes it quite convenient to work with
        // date fields without having to create a mutator for each property.
        if (in_array($key, $this->getDates()) &&
            ! is_null($value)) {
            return $this->asDateTime($value);
        }

        return $value;
    }
}
