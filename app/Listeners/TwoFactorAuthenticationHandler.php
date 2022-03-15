<?php

namespace App\Listeners;

use App\Models\User;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;
use LaravelWebauthn\Facades\Webauthn;

class TwoFactorAuthenticationHandler
{
    /**
     * Handle the event.
     *
     * @param  \Laravel\Fortify\Events\TwoFactorAuthenticationChallenged  $event
     * @return void
     */
    public function handle(TwoFactorAuthenticationChallenged $event)
    {
        $this->registerWebauthn($event->user);
    }

    /**
     * Force register Webauthn login.
     *
     * @param  User  $user
     */
    private function registerWebauthn(User $user)
    {
        if (Webauthn::enabled($user)) {
            Webauthn::login($user);
        }
    }
}
