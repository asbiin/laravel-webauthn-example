<?php

namespace App\Listeners;

use LaravelWebauthn\Events\WebauthnRegisterFailed;
use Illuminate\Support\Facades\Log;

class WebauthnRegisterFailedListener
{
    /**
     * Handle the event.
     *
     * @param  \LaravelWebauthn\Events\WebauthnRegisterFailed  $event
     * @return void
     */
    public function handle(WebauthnRegisterFailed $event)
    {
        Log::error('Webauthn error: '.get_class($event) , $event->exception ? ['exception' => $event->exception] : []);
    }
}
