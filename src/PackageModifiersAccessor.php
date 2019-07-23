<?php
namespace Webcosmonauts\Alder;

use Illuminate\Support\Str;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Facades\Alder;

class PackageModifiersAccessor
{
    public $leaf;
    public $package;

    /**
     * PackageModifiersAccessor constructor.
     * @param Leaf $leaf
     * @param string $package
     */
    public function __construct(Leaf $leaf, $package) {
        $this->leaf    = $leaf;
        $this->package = $package;
    }

    public function __get($key) {
        $relation = Str::studly($this->package).'/'.$key;
        $related = $this->leaf->getRelationValue($relation);
        if($related) return $related;
        $related = $this->leaf->hasOne(
            Alder::getPackageModifier($this->package, $key),
            'id',
            'id'
        )->getResults();
        $this->leaf->setRelation($relation, $related);
        return $related;
    }
}
