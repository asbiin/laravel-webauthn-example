<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
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
            ? Response::json([
                'publicKey' => $this->publicKey
            ])
            : $this->inertiaResponse($request)
                ->toResponse($request);
    }

    /**
     * Get inertia response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    private function inertiaResponse(Request $request): \Inertia\Response
    {
        return $request->user() !== null
            ? Inertia::render('Webauthn/WebauthnLogin', [
                'publicKey' => $this->publicKey,
                'remember' => Auth::guard()->viaRemember(),
            ])
            : Inertia::render('Auth/Login', [
                'publicKey' => $this->publicKey,
            ]);
    }
}
