<?php

namespace App\Listeners;

use App\Notifications\NewUserAlert;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use VincentBean\LaravelPlausible\PlausibleEvent;

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

            PlausibleEvent::fire('register', [
                'username' => $event->user->name
            ]);
        }

        if (($notifier = config('mail.notifier')) !== null) {
            Notification::route('mail', $notifier)
                ->notify(new NewUserAlert($event->user));
        }
    }
}
