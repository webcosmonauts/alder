<?php


namespace Webcosmonauts\Alder\Structure;


use InvalidArgumentException;

class RelationState
{
    public const DIFF_TYPE     = 'TYPE';
    public const DIFF_NULLABLE = 'NULLABLE';

    public const belongsTo     = 'belongsTo';
    public const belongsToMany = 'belongsToMany';
    public const hasOne        = 'hasOne';
    public const hasMany       = 'hasMany';
    public const types = [
        self::belongsTo,
        self::belongsToMany,
        self::hasOne,
        self::hasMany,
    ];

    public $type;
    public $nullable;
    public $leaf_type;

    public function __construct($relation) {
        $relation = (object)$relation;
        $this->type = $relation->type ?? null;
        if(($this->type == self::belongsToMany || $this->type == self::hasMany) && $this->nullable === false) {
            throw new InvalidArgumentException();
        }
        if(!isset($relation->leaf_type) || $relation->leaf_type == null) {
            throw new InvalidArgumentException();
        }
        $this->nullable  = $relation->nullable ?? true;
        $this->leaf_type = $relation->leaf_type;
    }

    public function canUpgradeSafe(RelationState $up) {
        if($this->type != $up->type) return false;
        if($this->type != $up->type) return false;
        if($this->nullable && !$up->nullable) return false;
        return true;
    }
}
