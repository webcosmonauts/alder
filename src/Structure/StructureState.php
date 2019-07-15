<?php

namespace Webcosmonauts\Alder\Structure;

use Illuminate\Support\Collection;

/**
 * Class StructureState
 * @package Webcosmonauts\Alder\Structure
 *
 * @property Collection $fields
 * @property Collection $relations
 */
class StructureState
{
    public $fields;
    public $relations;

    public function __construct($struct) {
        $struct = self::normalizeArgs($struct);
        $this->fields = collect($struct['fields'] ?? [])->mapWithKeys(function ($field, $key) {
            return [$key => new FieldState($field)];
        });

        $this->relations = collect($struct['relations'] ?? [])->mapWithKeys(function ($relation, $key) {
            return [$key => new RelationState($relation)];
        });
    }

    private static function normalizeArgs($struct) {
        if(!isset($struct['fields']))    $struct['fields']    = [];
        if(!isset($struct['relations'])) $struct['relations'] = [];
        if(!isset($struct['indexes']))   $struct['indexes']   = [];
        return $struct;
    }

    public function canUpgradeSafe(StructureState $up) {
        return
            $this->fields->diff($up)->count() == 0 &&
            $this->fields->every(function ($value, $key) use ($up) {
                $value->canUpgradeSafe($up->fields[$key]);
            });
    }
}
