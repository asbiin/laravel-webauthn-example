<?php

namespace App\Listeners;

use LaravelWebauthn\Events\WebauthnLogin;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Log;

class WebauthnLoginHandler
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * Handle the event.
     *
     * @param  \LaravelWebauthn\Events\WebauthnLogin  $event
     * @return void
     */
    public function handle(WebauthnLogin $event)
    {
        if ($event->user instanceof \App\Models\User) {
            Log::info("Webauthn login: {$event->user->name} {$event->user->email}");

            if ($event->user->hasEnabledTwoFactorAuthentication()) {
                $this->registerTwoFactor($event->user);
            }
        }
    }

    /**
     * Force register two factor login.
     *
     * @param  User  $user
     */
    private function registerTwoFactor(User $user)
    {
        $request = request();

        $remember = $request->session()->pull('login.remember', false);

        $this->guard->login($user, $remember);

        $request->session()->regenerate();
    }
}
