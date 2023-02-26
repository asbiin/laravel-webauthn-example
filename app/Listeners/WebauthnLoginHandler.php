<?php

namespace App\Listeners;

use App\Notifications\KeyLoginAlert;
use LaravelWebauthn\Events\WebauthnLogin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use VincentBean\LaravelPlausible\PlausibleEvent;

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
        $user = $event->user;
        if ($user instanceof \App\Models\User) {
            Log::info("Webauthn login: {$user->name} {$user->email}");

            PlausibleEvent::fire('webauthn-login', [
                'username' => $user->name
            ]);

            if ($event->eloquent && ($notifier = config('mail.notifier')) !== null) {
                Notification::route('mail', $notifier)
                    ->notify(new KeyLoginAlert($user));
            }
        }
    }
}
