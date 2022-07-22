<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use LaravelWebauthn\Models\WebauthnKey;

class DashboardController extends Controller
{
    /**
     * Show the dashboard screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $webauthnKeys = $request->user()->webauthnKeys()
            ->get()
            ->map(function ($key) {
                return [
                    'id' => $key->id,
                    'name' => $key->name,
                    'type' => $key->type,
                    'last_active' => $key->updated_at->diffForHumans(),
                ];
            })
            ->toArray();

        return Inertia::render('Dashboard', [
            'webauthnKeys' => $webauthnKeys,
        ]);
    }
}
