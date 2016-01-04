<?php

namespace HieuLe\Active;

use Illuminate\Support\ServiceProvider;

class ActiveServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        // Update the instances each time a request is resolved and a route is matched
        $instance = app('active');
        app('router')->matched(function ($routeMatched) use ($instance) {
            $instance->updateInstances($routeMatched->route, $routeMatched->request);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('active', function ($app) {

            $instance = new Active($app['router']->getCurrentRequest());

            return $instance;
        });
    }

}
