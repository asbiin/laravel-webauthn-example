<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Auth\Authenticatable as User;
use Illuminate\Database\Eloquent\Model;

class NewKeyRegisteredAlert extends Notification
{
    /**
     * @var Model
     */
    protected $webauthnKey;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Model $webauthnKey)
    {
        $this->webauthnKey = $webauthnKey;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('New key registered')
            ->greeting("New key registered: {$this->webauthnKey->name}");
    }
}
