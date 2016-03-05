<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

use App\Models\Entity;
use App\Models\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['Dingo\Api\Exception\Handler']->setErrorFormat([
            'error' => [
                'message' => ':message',
                'errors' => ':errors',
                'code' => ':code',
                'status_code' => ':status_code',
                'debug' => ':debug'
            ]
        ]);
        $this->app->configure('liquid');
    }

    public function boot()
    {
        $this->app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            return new \App\Formatters\Adapter(
                (new \League\Fractal\Manager)->setSerializer(new \League\Fractal\Serializer\DataArraySerializer),
                'include', ',', false
            );
        });
    }
}
