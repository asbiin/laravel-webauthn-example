<?php

namespace App\Providers;

use App\Listeners\RegisteredHandler;
use App\Listeners\TwoFactorAuthenticationHandler;
use App\Listeners\LogFailedEvent;
use App\Listeners\WebauthnLoginHandler;
use App\Listeners\WebauthnRegisterHandler;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use LaravelWebauthn\Events\WebauthnLogin;
use LaravelWebauthn\Events\WebauthnLoginFailed;
use LaravelWebauthn\Events\WebauthnRegister;
use LaravelWebauthn\Events\WebauthnRegisterFailed;
use LaravelWebauthn\Listeners\LoginViaRemember;

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
            LoginViaRemember::class,
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
            LogFailedEvent::class,
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
