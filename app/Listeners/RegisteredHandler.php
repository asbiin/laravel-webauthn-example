<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\NewUserAlert;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;

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
        if (($notifier = config('mail.notifier')) !== null) {
            Notification::route('mail', $notifier)
                ->notify(new NewUserAlert($event->user));
        }
    }
}
