<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
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
        return $request->wantsJson()
            ? Response::json(['publicKey' => $this->publicKey])
            : Inertia::render('Webauthn/WebauthnLogin', [
                'publicKey' => $this->publicKey,
                'remember' => Auth::guard()->viaRemember(),
            ])->toResponse($request);
    }
}
