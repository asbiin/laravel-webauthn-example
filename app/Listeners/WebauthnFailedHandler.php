<?php

namespace App\Listeners;

use LaravelWebauthn\Events\AbstractEventFailed;
use Illuminate\Support\Facades\Log;

class WebauthnFailedHandler
{
    /**
     * Handle the event.
     *
     * @param  \LaravelWebauthn\Events\WebauthnLogin  $event
     * @return void
     */
    public function handle(AbstractEventFailed $event)
    {
        Log::error('Webauthn error: '.get_class($event) , ['exception' => $event->exception]);
    }
}
