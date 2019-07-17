<?php
namespace Webcosmonauts\Alder\Structure;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;
use Webcosmonauts\Alder\Models\Modifiers\BaseModifier;
use Webcosmonauts\Alder\Models\SchemaColumn;

class AlderScheme
{
    public const typeMapper = [
        'checkbox'        => 'boolean',
        'color'           => 'string',
        'datetime-local'  => 'dateTimeTz',
        'file'            => 'string',
        'file-multiple'   => 'string',
        'image'           => 'string',
        'month'           => 'string',
        'number'          => 'float',
        'password'        => 'string',
        'repeater'        => 'text',
        'select'          => 'string',
        'select-multiple' => 'string',
        'template'        => 'text',
    ];

    public function canUpgradeSafe($table, StructureState $up) {
        return $this->fromTable($table)->canUpgradeSafe($up);
    }

    public function upgrade(string $modifier, StructureState $up = null) {
        /* @var $modifier BaseModifier */
        if($up == null) $up = $this->fromModifier($modifier);
        $table_name = $modifier::getTableName();
        $table_name_trans = $modifier::getTableNameTranslatable();
        $prefix = $modifier::prefix;
        DB::transaction(function () use ($table_name, $table_name_trans, $prefix, $up) {
            $nonTranslatable = $up->getNonTranslatable();
            if ($nonTranslatable->count() + $up->getBelongsTo()->count() > 0) {
                $this->setTable($table_name, function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->foreign('id')->references('id')->on('leaves');
                    $table->timestamps();
                });
                Schema::table($table_name, function (Blueprint $table) use ($nonTranslatable, $table_name, $up) {
                    /**
                     * @var string $name
                     * @var FieldState $field
                     */
                    foreach ($nonTranslatable as $name => $field) {
                        /* @var string $type */
                        $type = $this->convertType($field->type);
                        $this->setColumn($table, [
                            'name' => $name,
                            'type' => $type,
                            'nullable' => $field->nullable,
                            'default'  => $field->default,
                            'unique'   => $field->unique,
                        ]);
                    }
                    // Collection of columns that not present in new StructureState
                    /** @var Collection $toDelete */
                    $toDelete = $this->fromTable($table_name)->getNonTranslatable()->keys()->diff($up->fields->keys());
                    /** @var string $name */
                    foreach ($toDelete as $name) {
                        $table->removeColumn($name);
                    }

                    /** @var Collection $pointers */
                    $pointers = $up->getBelongsTo();
                    /**
                     * @var string $name
                     * @var RelationState $relation
                     */
                    foreach ($pointers as $name => $relation) {
                        /* @var Fluent $schemaColumn */
                        $schemaColumn = $table->integer($name);
                    	if (Schema::hasColumn($table_name, $name)) $schemaColumn->change();
                        $table->foreign($name)->references('id')->on('leaves');
                    }
                });
            }


            $translatable = $up->getTranslatable();
            if ($translatable->count() > 0) {
                $this->setTable($table_name_trans, function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->foreign('id')->references('id')->on('leaves');
                    $table->timestamps();
                });
                Schema::table($table_name_trans, function (Blueprint $table) use ($translatable, $table_name_trans, $up) {
                    /**
                     * @var string $name
                     * @var FieldState $field
                     */
                    foreach ($translatable as $name => $field) {
                        /* @var string $type */
                        $type = $this->convertType($field->type);
                        $this->setColumn($table, [
                            'name' => $name,
                            'type' => $type,
                            'nullable' => $field->nullable,
                            'default'  => $field->default,
                            'unique'   => $field->unique,
                        ]);
                    }
                    // Collection of columns that not present in new StructureState
                    /** @var Collection $toDelete */
                    $toDelete = $this->fromTable($table_name_trans)->getTranslatable()->keys()->diff($up->fields->keys());
                    /** @var string $name */
                    foreach ($toDelete as $name) {
                        $table->removeColumn($name);
                    }
                });
            }

            $mtms = $up->getBelongsToMany();
            /**
             * @var string $name
             * @var RelationState $relation
             */
            foreach ($mtms as $name => $relation) {
                $mtm_table_name = $prefix .'__mtm__'. $name;
                $this->setTable($mtm_table_name, function (Blueprint $table) {
                    $table->bigInteger('source_id')->unsigned();
                    $table->foreign('source_id')->references('id')->on('leaves');
                    $table->bigInteger('target_id')->unsigned();
                    $table->foreign('target_id')->references('id')->on('leaves');
                });
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

    public function convertType(string $type) {
        return self::typeMapper[$type] ?? $type;
    }

    public function fromModifier(string $modifier) {
        return new StructureState($modifier::structure);
    }

    /**
     * @param string $table_name
     * @param Closure $callback
     */
    public function setTable($table_name, $callback = null) {
        if(!Schema::hasTable($table_name)) {
            Schema::create($table_name, $callback);
        }
    }

    /**
     * @param Blueprint $table
     * @param array $options
     * @return Fluent
     */
    public function setColumn(Blueprint $table, $options) {
        /** @var Fluent $schemaColumn */
        $schemaColumn = $table->{$options['type']}($options['name']);
        if($options['nullable'] ?? false) $schemaColumn->nullable();
        if($options['default']  ?? false) $schemaColumn->default($options['default']);
        if($options['unique']   ?? false) $schemaColumn->unique();
        if(Schema::hasColumn($table->getTable(), $options['name'])) $schemaColumn->change();

        SchemaColumn::unguard();
        $schemaColumn = SchemaColumn::firstOrNew([ 'table' => $table->getTable(), 'column' => $options['name'] ]);
        $schemaColumn->fill([
            'type'     => $options['type'],
            'nullable' => $options['nullable'],
            'default'  => $options['default'],
        ]);
        $schemaColumn->save();
        SchemaColumn::reguard();

        return $schemaColumn;
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
                $table->increments('id');
                $table->string('table');
                $table->string('column');
                $table->string('type');
                $table->boolean('nullable');
                $table->string('default')->nullable();
                $table->unique(['table', 'column']);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('schema_indexes')) {
            Schema::create('schema_indexes', function(Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('table');
                $table->string('type');
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('schema_columns_indexes')) {
            Schema::create('schema_columns_indexes', function(Blueprint $table) {
                $table->increments('id');
                $table->string('table');
                $table->string('column');
                $table->integer('index');
                $table->unique(['table', 'column', 'index']);
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('schema_keys')) {
            Schema::create('schema_keys', function(Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('table');
                $table->string('column');
                $table->string('foreign_table');
                $table->string('foreign_column');
                $table->string('on_delete');
                $table->string('on_update');
                $table->unique(['table', 'column']);
                $table->timestamps();
            });
        }
    }
}
