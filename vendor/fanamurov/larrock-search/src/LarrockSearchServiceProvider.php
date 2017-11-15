<?php

namespace Larrock\ComponentSearch;

use Illuminate\Support\ServiceProvider;
use Larrock\ComponentSearch\Middleware\SiteSearch;
use Larrock\ComponentSearch\Middleware\SiteSearchAdmin;

class LarrockSearchServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->loadViewsFrom(__DIR__.'/views', 'larrock');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/larrock'),
            __DIR__.'/config/larrock-admin-search.php' => config_path('larrock-admin-search.php'),
            __DIR__.'/config/larrock-search.php' => config_path('larrock-search.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom( __DIR__.'/config/larrock-admin-search.php', 'larrock-admin-search');
        $this->mergeConfigFrom( __DIR__.'/config/larrock-search.php', 'larrock-search');

        $this->app['router']->aliasMiddleware('SiteSearch', SiteSearch::class);
        $this->app['router']->aliasMiddleware('SiteSearchAdmin', SiteSearchAdmin::class);
    }
}
