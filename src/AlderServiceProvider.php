<?php
namespace Webcosmonauts\Alder;

use Illuminate\Support\ServiceProvider;

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
            __DIR__.'/../public' => public_path(),
        ], 'public');
        
        // translations
        $this->loadTranslationsFrom(__DIR__.'\..\resources\lang', 'alder');
    
        // migrations
        $this->loadMigrationsFrom(__DIR__.'\..\database\migrations');
        
        // views
        $this->loadViewsFrom(__DIR__.'\..\resources\views', 'alder');
    }
    /**
     * Make config publishment optional by merging the config from the package.
     *
     * @return  void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/alder.php', 'alder');
        
        $this->app->bind('alder', function () {
            return new Alder();
        });
    }
}
