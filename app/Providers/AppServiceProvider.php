<?php

namespace App\Providers;

use App\Http\Responses\LoginViewResponse;
use App\Http\Responses\RegisterViewResponse;
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
        Webauthn::registerViewResponseUsing(RegisterViewResponse::class);
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
