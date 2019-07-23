<?php

namespace Webcosmonauts\Alder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Str;
use Webcosmonauts\Alder\Commands\Test;
use Webcosmonauts\Alder\Commands\UpgradeDatabaseStateCommand;
use Webcosmonauts\Alder\Http\Controllers\LeavesController\LeafEntityController;
use Webcosmonauts\Alder\Http\Controllers\NotificationController;
use Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController;
use Webcosmonauts\Alder\Http\Middleware\AlderGuard;
use Webcosmonauts\Alder\Http\Middleware\isAdmin;
use Webcosmonauts\Alder\Http\Middleware\LocaleSwitcher;
use Webcosmonauts\Alder\Models\Leaf;
use Webcosmonauts\Alder\Models\Modifiers\SeoKeywordModifier;
use Webcosmonauts\Alder\Structure\AlderScheme;
use Webcosmonauts\Alder\Facades\Alder;
use Webcosmonauts\Alder\Structure\StructureState;

class LeafRegister
{
    public static function register()
    {
//        foreach(Alder::getModifiersNames() as $modifier_name => $modifier) {
//
//        }

        Builder::macro('hasMacro', function($macro) {
            return isset(static::$macros[$macro]);
        });

        Builder::macro('modifiers', function($modifiers) {
            Arr::wrap($modifiers);
            foreach ($modifiers as $modifier) {
                $this->has($modifier);
            }

            return $this->with($modifiers);
        });

//        Builder::macro('withModifiers', function($modifiers) {
//            Arr::wrap($modifiers);
//
//            foreach ($modifiers as $modifierName) {
//                [$pack, $modifier] = AlderFacade::parseModifierName($modifierName);
//                $tbl = $modifier::getTableName();
//                $tbl_trans = $modifier::getTableNameTranslatable();
//                if (Schema::hasTable($tbl)) $this->join($tbl, $tbl . '.id', '=', 'leaves.id');
//                if (Schema::hasTable($tbl_trans)) $this->join($tbl_trans, $tbl_trans . '.id', '=', 'leaves.id');
//            }
//
//            $result = $this->select('leaves.*')->get();
//
//            foreach ($modifiers as $modifierName) {
//                [$pack, $modifier] = AlderFacade::parseModifierName($modifierName);
//                $ids = $result->pluck('id');
//                $models = $modifier::find($ids);
//                $relation_name = $modifierName;
//                foreach ($result as $leaf) {
//                    $relation = $models->find($leaf->getKey());
//                    $leaf->setRelation($relation_name, $relation);
//                }
//            }
//            return $result;
//        });
    }
}
