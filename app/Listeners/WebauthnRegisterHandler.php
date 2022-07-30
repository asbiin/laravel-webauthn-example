<?php

namespace App\Listeners;

use App\Notifications\NewKeyRegisteredAlert;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
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

        // if (($notifier = config('mail.notifier')) !== null) {
        //     Notification::route('mail', $notifier)
        //         ->notify(new NewKeyRegisteredAlert($event->webauthnKey));
        // }
    }
}
