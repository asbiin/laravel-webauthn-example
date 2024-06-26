<?php

namespace App\Listeners;

use App\Notifications\NewUserAlert;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Pirsch\Facades\Pirsch;

class RegisteredHandler
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if ($event->user instanceof \App\Models\User) {
            Log::info("Webauthn register: {$event->user->name} {$event->user->email}");

            Pirsch::track('register', [
                'username' => $event->user->name
            ]);
        }

        if (($notifier = config('mail.notifier')) !== null) {
            Notification::route('mail', $notifier)
                ->notify(new NewUserAlert($event->user));
        }
    }
}
