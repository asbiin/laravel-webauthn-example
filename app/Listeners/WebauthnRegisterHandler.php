<?php

namespace App\Listeners;

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
    }
}
