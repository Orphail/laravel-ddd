<?php

namespace Src\Common\Infrastructure\Laravel\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('success', function ($data, $code = HttpResponse::HTTP_OK) {
            if ($data instanceof \JsonSerializable) {
                $data = $data->jsonSerialize();
            }
            return response()->json($data, $code);
        });

        Response::macro('error', function ($message, $code = HttpResponse::HTTP_BAD_REQUEST) {
            return response()->json(['error' => $message], $code);
        });
    }
}
