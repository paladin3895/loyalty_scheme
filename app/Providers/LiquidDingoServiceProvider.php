<?php
namespace App\Providers;

use App\Dispatcher;
use Dingo\Api\Provider\LumenServiceProvider;

class LiquidDingoServiceProvider extends LumenServiceProvider
{
    /**
     * Register the internal dispatcher.
     *
     * @return void
     */
    public function registerDispatcher()
    {
        $this->app->singleton('api.dispatcher', function ($app) {
            $dispatcher = new Dispatcher($app, $app['files'], $app['api.router'], $app['api.auth']);

            $config = $app['config']['api'];

            $dispatcher->setSubtype($config['subtype']);
            $dispatcher->setStandardsTree($config['standardsTree']);
            $dispatcher->setPrefix($config['prefix']);
            $dispatcher->setDefaultVersion($config['version']);
            $dispatcher->setDefaultDomain($config['domain']);
            $dispatcher->setDefaultFormat($config['defaultFormat']);

            return $dispatcher;
        });
    }
}
