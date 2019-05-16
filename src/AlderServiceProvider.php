<?php
namespace Webcosmonauts\Alder;

use Illuminate\Support\ServiceProvider;
use Webcosmonauts\Classes\Alder;

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
