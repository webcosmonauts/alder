<?php
namespace Webcosmonauts\Alder;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Webcosmonauts\Alder\Http\Controllers\LeavesController\LeafEntityController;

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
            __DIR__.'/../config/alder.php' => config_path('alder.php'),
            __DIR__.'/../config/file-manager.php' => config_path('file-manager.php'),
        ], 'alder-config');
        
        // public folder
        $this->publishes([
            __DIR__.'/../public/css' => public_path().'/css',
            __DIR__.'/../public/js' => public_path().'/js',
            __DIR__.'/../public/sprites' => public_path().'/sprites',
            __DIR__.'/../public/svgs' => public_path().'/svgs',
            __DIR__.'/../public/webfonts' => public_path().'/webfonts',
            __DIR__.'/../public/file-manager' => public_path().'/vendor/file-manager',
            __DIR__.'/../public/contact-form' => public_path().'/vendor/contact-form',
            __DIR__.'/../public/LCMs' => public_path().'/vendor/LCMs',
            
        ], 'public');
        
        // translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'alder');
    
        // migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        
        // views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'alder');
    }
    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/alder.php', 'alder');

        $loader = AliasLoader::getInstance();

        $this->app->bind('alder', function () {
            return new Alder();
        });
        
        $this->app->bind('leaf_helper', function () {
            return new LeafEntityController();
        });

        $loader->alias("TemplateHelper", "Webcosmonauts\\Alder\\Facades\\TemplateHelper");

        $this->app->bind('template_helper', function () {
            return new \Webcosmonauts\Alder\Http\Controllers\TemplateControllers\TemplateController();
        });
    }

}
