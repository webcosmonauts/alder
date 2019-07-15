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

    /**
     * StructureState constructor.
     * @param $struct
     */
    public function __construct($struct) {
        $struct = self::normalizeArgs($struct);
        $this->fields = collect($struct['fields'] ?? [])->mapWithKeys(function ($field, $key) {
            return [$key => new FieldState($field)];
        });

        $this->relations = collect($struct['relations'] ?? [])->mapWithKeys(function ($relation, $key) {
            return [$key => new RelationState($relation)];
        });
    }

    /**
     * @param $struct
     * @return array
     */
    private static function normalizeArgs($struct) {
        if(!isset($struct['fields']))    $struct['fields']    = [];
        if(!isset($struct['relations'])) $struct['relations'] = [];
        if(!isset($struct['indexes']))   $struct['indexes']   = [];
        return $struct;
    }

    /**
     * @param StructureState $up
     * @return bool
     */
    public function canUpgradeSafe(StructureState $up) {
        return
            $this->fields->diff($up)->count() == 0 &&
            $this->fields->every(function ($value, $key) use ($up) {
                $value->canUpgradeSafe($up->fields[$key]);
            });
    }

    /**
     * @return Collection
     */
    public function getNonTranslatable() {
        return $this->fields->filter(function ($field) { return !$field->translatable; });
    }

    /**
     * @return Collection
     */
    public function getTranslatable() {
        return $this->fields->filter(function ($field) { return $field->translatable; });
    }
}
