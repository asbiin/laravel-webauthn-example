<?php

namespace App\Providers;

use App\Listeners\LoginListener;
use App\Listeners\RegisteredHandler;
use App\Listeners\TwoFactorAuthenticationHandler;
use App\Listeners\WebauthnLoginHandler;
use App\Listeners\WebauthnRegisterHandler;
use App\Listeners\WebauthnRegisterFailedListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use LaravelWebauthn\Events\WebauthnLogin;
use LaravelWebauthn\Events\WebauthnRegister;
use LaravelWebauthn\Events\WebauthnRegisterFailed;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            RegisteredHandler::class,
        ],
        Login::class => [
            LoginListener::class,
        ],
        TwoFactorAuthenticationChallenged::class => [
            TwoFactorAuthenticationHandler::class,
        ],
        WebauthnLogin::class => [
            WebauthnLoginHandler::class,
        ],
        WebauthnRegister::class => [
            WebauthnRegisterHandler::class,
        ],
        WebauthnRegisterFailed::class => [
            WebauthnRegisterFailedListener::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
    ];
}
