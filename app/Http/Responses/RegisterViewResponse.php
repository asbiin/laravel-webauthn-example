<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Response;
use Inertia\Inertia;
use LaravelWebauthn\Http\Responses\RegisterViewResponse as RegisterViewResponseBase;
use LaravelWebauthn\Models\WebauthnKey;

class RegisterViewResponse extends RegisterViewResponseBase
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $webauthnKeys = WebauthnKey::where('user_id', $request->user()->id)->get();

        return $request->wantsJson()
            ? Response::json([
                'publicKey' => $this->publicKey
            ])
            : Inertia::render('Dashboard', [
                'webauthnKeys' => $webauthnKeys,
                'publicKey' => $this->publicKey
            ])->toResponse($request);
    }
}
