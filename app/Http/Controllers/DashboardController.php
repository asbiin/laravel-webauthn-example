<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

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
            ->map(fn ($key) => [
                'id' => $key->id,
                'name' => $key->name,
                'type' => $key->type,
                'used_at' => optional($key->used_at)->diffForHumans(),
            ])
            ->toArray();

        return Inertia::render('Dashboard', [
            'webauthnKeys' => $webauthnKeys,
        ]);
    }
}
