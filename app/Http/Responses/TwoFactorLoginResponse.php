<?php

namespace App\Http\Responses;

use Laravel\Fortify\Http\Responses\TwoFactorLoginResponse as TwoFactorLoginResponseBase;
use LaravelWebauthn\Facades\Webauthn;

class TwoFactorLoginResponse extends TwoFactorLoginResponseBase
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        Webauthn::login($request->user());

        return parent::toResponse($request);
    }
}
