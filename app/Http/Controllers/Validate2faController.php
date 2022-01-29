<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use LaravelWebauthn\Models\WebauthnKey;

class Validate2faController extends Controller
{
    /**
     * Show the dashboard screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $webauthnKeys = WebauthnKey::where('user_id', $request->user()->id)->get();

        return Inertia::render('Dashboard', [
            'webauthnKeys' => $webauthnKeys,
        ]);
    }
}
