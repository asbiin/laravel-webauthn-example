<?php

namespace App\Listeners;

use LaravelWebauthn\Events\WebauthnLogin;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Features;

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
        $user = $event->user;
        if ($user instanceof \App\Models\User) {
            Log::info("Webauthn login: {$user->name} {$user->email}");

            if (Features::enabled(Features::twoFactorAuthentication())
                && $user->hasEnabledTwoFactorAuthentication()) {
                $this->registerTwoFactor($user);
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
        session([
            'login.id' => $user->getAuthIdentifier(),
            'login.remember' => Auth::viaRemember(),
        ]);
    }
}
