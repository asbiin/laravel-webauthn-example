<?php

namespace App\Listeners;

use App\Models\WebauthnKey;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Webauthn\Event\AuthenticatorAssertionResponseValidationSucceededEvent;

class WebauthnAuthenticateListener
{
    /**
     * Handle webauthn used key.
     *
     * @return void
     */
    public function handle(AuthenticatorAssertionResponseValidationSucceededEvent $event)
    {
        $key = WebauthnKey::where('user_id', $event->userHandle)
            ->where('credentialId', Base64UrlSafe::encode($event->publicKeyCredentialSource->publicKeyCredentialId))
            ->firstOrFail();
        $key->update(['used_at' => now()]);
        $key->save();
    }
}
