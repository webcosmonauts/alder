<?php
namespace Webcosmonauts\Alder\Structure;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Webcosmonauts\Alder\Models\Modifiers\BaseModifier;
use Webcosmonauts\Alder\Models\SchemaColumn;

class AlderScheme
{
    public const typeMapper = [
        'file' => 'string',
    ];

    public function canUpgradeSafe($table, StructureState $up) {
        return $this->fromTable($table)->canUpgradeSafe($up);
    }

    public function upgrade(string $modifier, StructureState $up) {
        $table_name = $modifier::getTableName();
        $prefix = $modifier::prefix;
        DB::transaction(function () use ($table_name, $prefix, $up) {
            if (!Schema::hasTable($table_name)) {
                Schema::create($table_name, function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->foreign('id')->references('id')->on('leaves');
                });
            }
            Schema::table($table_name, function (Blueprint $table) use ($up, $table_name) {
                foreach ($up->fields as $name => $field) {
                    /* @var string $type */
                    $type = self::typeMapper[$field->type];
                    /* @var Fluent $schemaColumn */
                    $schemaColumn = $table->{$type}($name);
                    if($field->nullable ?? false) $schemaColumn->nullable();
                    if($field->default ?? false) $schemaColumn->default($field->default);
                    if($field->unique ?? false) $schemaColumn->unique();
                    if(Schema::hasColumn($table_name, $name)) $schemaColumn->change();
                }
                // Collection of colum
                //ns that not present in new StructureState
                $toDelete = $this->fromTable($table_name)->fields->keys()->diff($up->fields->keys());
                foreach ($toDelete as $name) {

                    $table->removeColumn($name);
                }

                $pointers = $up->relations->filter(function ($rel) { return $rel->type == RelationState::belongsTo; });
                foreach ($pointers as $name => $relation) {
                    /* @var Fluent $schemaColumn */
                    $schemaColumn = $table->integer($name);
                    if(Schema::hasColumn($table_name, $name)) $schemaColumn->change();
                    $table->foreign($name)->references('id')->on('leaves');
                }
            });
            $mtms = $up->relations->filter(function ($rel) { return $rel->type == RelationState::belongsToMany; });
            foreach ($mtms as $name => $relation) {
                $mtm_table_name = $prefix .'__mtm_'. $name;
                if (!Schema::hasTable($mtm_table_name)) {
                    Schema::create($mtm_table_name, function (Blueprint $table) {
                        $table->integer('source_id');
                        $table->foreign('source_id')->references('id')->on('leaves');
                        $table->integer('target_id');
                        $table->foreign('target_id')->references('id')->on('leaves');
                    });
                }
            }
        });
    }

    public function fromTable($name) {
        $columns = SchemaColumn::where('table', $name)->get();
        $columns = $columns->mapWithKeys(function($column) {
            return [
                $column->column => [
                    'type'     => $column->type,
                    'nullable' => $column->nullable,
                    'default'  => $column->tydefaultpe,
                    'indexed'  => false,
                    'unique'   => false,
                ]
            ];
        });
        return new StructureState([
            'fields' => $columns
        ]);
    }

    public function isSystemSet() {
        return Schema::hasTable('schema_columns')
            && Schema::hasTable('schema_indexes')
            && Schema::hasTable('schema_columns_indexes')
            && Schema::hasTable('schema_keys');
    }

    public function setupSystem() {
        if(!Schema::hasTable('schema_columns')) {
            Schema::create('schema_columns', function(Blueprint $table) {
                $table->string('table');
                $table->string('column');
                $table->string('type');
                $table->boolean('nullable');
                $table->string('default');
                $table->unique(['table', 'column']);
            });
        }

        if(!Schema::hasTable('schema_indexes')) {
            Schema::create('schema_indexes', function(Blueprint $table) {
                $table->string('name')->unique();
                $table->string('table');
                $table->string('type');
            });
        }

        if(!Schema::hasTable('schema_columns_indexes')) {
            Schema::create('schema_columns_indexes', function(Blueprint $table) {
                $table->string('table');
                $table->string('column');
                $table->integer('index');
                $table->unique(['table', 'column', 'index']);
            });
        }

        if(!Schema::hasTable('schema_keys')) {
            Schema::create('schema_keys', function(Blueprint $table) {
                $table->string('name')->unique();
                $table->string('table');
                $table->string('column');
                $table->string('foreign_table');
                $table->string('foreign_column');
                $table->string('on_delete');
                $table->string('on_update');
                $table->unique(['table', 'column']);
            });
        }
    }
}
