<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use LaravelWebauthn\Events\WebauthnRegister;

class WebauthnRegisterHandler
{
    /**
     * Handle the event.
     *
     * @param  \LaravelWebauthn\Events\WebauthnRegister  $event
     * @return void
     */
    public function handle(WebauthnRegister $event)
    {
        Log::info("Webauthn register: {$event->webauthnKey->name}");

        Cookie::queue('webauthn_remember', $event->webauthnKey->user_id, 60 * 24 * 30);

        // if (($notifier = config('mail.notifier')) !== null) {
        //     Notification::route('mail', $notifier)
        //         ->notify(new NewKeyRegisteredAlert($event->webauthnKey));
        // }
    }
}
