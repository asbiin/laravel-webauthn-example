<?php

namespace App\Listeners;

use LaravelWebauthn\Events\WebauthnLogin;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Support\Facades\Log;

class WebauthnLoginHandler
{
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
        session()->put([
            'login.id' => $user->getAuthIdentifier(),
            'login.remember' => true,
        ]);
    }
}
