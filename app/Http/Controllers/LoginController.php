<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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
        $webauthnRemember = $request->cookie('webauthn_remember');
        $data = [];
        if ($webauthnRemember) {
            if (($user = User::find($webauthnRemember)) && $user->webauthnKeys()->count() > 0) {
                $data['publicKey'] = Webauthn::prepareAssertion($user);
                $data['userName'] = $user->name;
            } else {
                Cookie::expire('webauthn_remember');
            }
        }

        return Inertia::render('Auth/Login', $data + [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }
}
