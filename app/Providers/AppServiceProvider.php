<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['Dingo\Api\Exception\Handler']->register(function (\Symfony\Component\HttpKernel\Exception\HttpException $exception) {
            return new Response([
                'status' => 0,
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getStatusCode(),
            ], $exception->getStatusCode());
        });
    }

    public function boot()
    {
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            return new \App\Formatters\Adapter(
                (new \League\Fractal\Manager)->setSerializer(new \League\Fractal\Serializer\DataArraySerializer),
                'include', ','
            );
        });
    }
}
