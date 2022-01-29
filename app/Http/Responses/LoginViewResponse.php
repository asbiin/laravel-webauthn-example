<?php

namespace App\Http\Responses;

use Inertia\Inertia;
use LaravelWebauthn\Http\Responses\LoginViewResponse as LoginViewResponseBase;

class LoginViewResponse extends LoginViewResponseBase
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $publicKey = $this->publicKeyRequest($request);

        return Inertia::render('Webauthn/WebauthnLogin', [
            'publicKey' => $publicKey
        ])->toResponse($request);
    }
}
