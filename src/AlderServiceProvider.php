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
use Webcosmonauts\Alder\Models\Modifiers\SeoKeywordModifier;
use Webcosmonauts\Alder\Structure\AlderScheme;
use Webcosmonauts\Alder\Facades\Alder as AlderFacade;
use Webcosmonauts\Alder\Structure\StructureState;

class AlderServiceProvider extends ServiceProvider
{
    /**
     * Publishes configuration file.
     *
     * @return  void
     */
    public function boot()
    {
        // config
        $this->publishes([
            __DIR__ . '/../config/alder.php' => config_path('alder.php'),
            __DIR__ . '/../config/file-manager.php' => config_path('file-manager.php'),
        ], 'alder-config');

        // public folder
        $this->publishes([
            __DIR__ . '/../public/css' => public_path() . '/css',
            __DIR__ . '/../public/js' => public_path() . '/js',
            __DIR__ . '/../public/sprites' => public_path() . '/sprites',
            __DIR__ . '/../public/svgs' => public_path() . '/svgs',
            __DIR__ . '/../public/webfonts' => public_path() . '/webfonts',
            __DIR__ . '/../public/material-dashboard' => public_path() . '/material-dashboard',
            __DIR__ . '/../public/file-manager' => public_path() . '/vendor/file-manager',
            __DIR__ . '/../public/contact-form' => public_path() . '/vendor/contact-form',
            __DIR__ . '/../public/LCMs' => public_path() . '/vendor/LCMs',
            __DIR__ . '/../public/LCM-picker/' => public_path() . '/vendor/LCM-picker',
            __DIR__ . '/../public/LCM-switcher/' => public_path() . '/vendor/LCM-switcher',
            __DIR__ . '/../public/page-builder/' => public_path() . '/vendor/page-builder',
            __DIR__ . '/../public/menus/' => public_path() . '/vendor/menus',

        ], 'public');

        // translations
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'alder');

        // migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'alder');

        // locale switcher
        $this->app['router']->aliasMiddleware('locale-switcher', LocaleSwitcher::class);
        $this->app['router']->aliasMiddleware('AlderGuard', AlderGuard::class);

        AlderFacade::registerPackage('alder', config('alder.modifiers'));
    
        if ($this->app->runningInConsole()) {
            $this->commands([
                UpgradeDatabaseStateCommand::class,
                Test::class,
            ]);
        }
    }

    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/alder.php', 'alder');

        $loader = AliasLoader::getInstance();

        $this->app->singleton('alder', function () {
            return new Alder();
        });

        $this->app->bind('alderscheme', function () {
            return new AlderScheme();
        });

        $this->app->bind('leaf_helper', function () {
            return new LeafEntityController();
        });

        $loader->alias("TemplateHelper", "Webcosmonauts\\Alder\\Facades\\TemplateHelper");

        $this->app->bind('template_helper', function () {
            return new TemplateController();
        });

        // TODO: переместить метод в подходящее место
        Builder::macro('withModifiers', function($modifiers) {
            Arr::wrap($modifiers);

            foreach ($modifiers as $modifierName) {
                [$pack, $modifier] = AlderFacade::parseModifierName($modifierName);
                $tbl = $modifier::getTableName();
                $tbl_trans = $modifier::getTableNameTranslatable();
                if (Schema::hasTable($tbl)) $this->join($tbl, $tbl . '.id', '=', 'leaves.id');
                if (Schema::hasTable($tbl_trans)) $this->join($tbl_trans, $tbl_trans . '.id', '=', 'leaves.id');
            }

            $result = $this->select('leaves.*')->get();

            foreach ($modifiers as $modifierName) {
                [$pack, $modifier] = AlderFacade::parseModifierName($modifierName);
                $ids = $result->pluck('id');
                $models = $modifier::find($ids);
                $relation_name = $modifierName;
                foreach ($result as $leaf) {
                    $relation = $models->find($leaf->getKey());
                    $leaf->setRelation($relation_name, $relation);
                }
            }
            return $result;
        });
    }
}
