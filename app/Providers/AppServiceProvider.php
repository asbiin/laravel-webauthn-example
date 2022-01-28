<?php

namespace App\Providers;

use App\Http\Responses\LoginViewResponse;
use Illuminate\Support\ServiceProvider;
use LaravelWebauthn\Services\Webauthn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Webauthn::loginViewResponseUsing(LoginViewResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
