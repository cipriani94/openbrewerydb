<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Response::macro('api', function (
            int $status = 200,
            bool $success = true,
            array|null $data = null,
            string|null $message = null,
            array|null $errors = null,
            array|null $meta = null
        ) {
            $responseData = [
                'success' => $success,
                'data' => $data
            ];
            if (!is_null($message)) $responseData['message'] = $message;
            if (!is_null($errors)) $responseData['errors'] = $errors;
            if (!is_null($meta)) $responseData['meta'] = $meta;
            return response()->json($responseData, $status, []);
        });

        Http::macro('openbrewerydb', function () {
            return Http::timeout(20)->baseUrl('https://api.openbrewerydb.org/v1');
        });
    }
}
