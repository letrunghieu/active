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

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('active', function ($app) {

            $instance = new Active($app['router']->current());

            // Update the instances each time a request is resolved and a route is matched
            $app['router']->matched(function ($route, $request) use ($instance) {
                $instance->updateInstances($route, $request);
            });

            return $instance;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['active'];
    }

}
