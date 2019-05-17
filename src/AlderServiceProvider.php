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
        $this->publishes([
            __DIR__.'/../config/alder.php' => config_path('alder.php'),
        ], 'alder-config');
    
        // Load routes
        $this->loadRoutesFrom(__DIR__.'\Routes\alder.php');
    
        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'\database\migrations');
        
        // Load views
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
