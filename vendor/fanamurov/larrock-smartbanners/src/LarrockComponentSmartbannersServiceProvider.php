<?php

namespace Larrock\ComponentSmartbanners;

use Illuminate\Support\ServiceProvider;
use Larrock\ComponentSmartbanners\Middleware\Smartbanners;

class LarrockComponentSmartbannersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'larrock');

        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/larrock')
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('Smartbanners', Smartbanners::class);
    }
}
