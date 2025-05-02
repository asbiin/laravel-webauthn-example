<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use LaravelWebauthn\Facades\Webauthn;

class LoginController extends Controller
{
    /**
     * Display the login view.
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request): Response
    {
        $data = [];

        if (Webauthn::userless()) {
            $data['publicKey'] = Webauthn::prepareAssertion(null);
            $data['userless'] = true;
            $data['autologin'] = $request->cookie('return') === 'true';
        }

        return Inertia::render('Auth/Login', $data + [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }
}
