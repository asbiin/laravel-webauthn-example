<?php

namespace App\Listeners;

use LaravelWebauthn\Events\EventFailed;
use Illuminate\Support\Facades\Log;

class LogFailedEvent
{
    /**
     * Handle the event.
     *
     * @param  \LaravelWebauthn\Events\WebauthnLogin  $event
     * @return void
     */
    public function handle(EventFailed $event)
    {
        Log::error('Webauthn error: '.get_class($event) , $event->exception ? ['exception' => $event->exception] : []);
    }
}
