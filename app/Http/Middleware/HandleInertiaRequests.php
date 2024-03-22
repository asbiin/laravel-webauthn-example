<?php

namespace App\Http\Middleware;

use Composer\InstalledVersions;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return [
            ...parent::share($request),
            'appName' => fn() => config('app.name', 'Laravel'),
            'auth' => fn() => [
                'user' => auth()->user(),
            ],
            'laravelWebauthn' => fn () => [
                'version' => InstalledVersions::getPrettyVersion('asbiin/laravel-webauthn'),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sentry' => fn () => [
                'dsn' => config('sentry.dsn'),
                'release' => config('sentry.release'),
                'environment' => config('sentry.environment'),
                'sendDefaultPii' => config('sentry.send_default_pii'),
                'tracesSampleRate' => config('sentry.traces_sample_rate'),
            ],
        ];
    }
}
