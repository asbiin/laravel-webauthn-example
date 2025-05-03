<?php

namespace App\Http\Middleware;

use Composer\InstalledVersions;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
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
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    #[\Override]
    public function share(Request $request)
    {
        $this->storeCurrentUrl($request);

        return [
            ...parent::share($request),
            'appName' => fn() => config('app.name', 'Laravel'),
            'laravelWebauthn' => fn () => [
                'version' => InstalledVersions::getPrettyVersion('asbiin/laravel-webauthn'),
            ],
            'hasKey' => fn () => function () use ($request) {
                if (! $user = $request->user()) {
                    return null;
                }

                return (bool) optional($user->webauthnKeys())->count() > 0;
            },
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sentry' => fn () => [
                'dsn' => config('sentry.dsn'),
                'tunnel' => config('sentry-tunnel.tunnel-url'),
                'release' => config('sentry.release'),
                'environment' => config('sentry.environment'),
                'sendDefaultPii' => config('sentry.send_default_pii'),
                'tracesSampleRate' => config('sentry.traces_sample_rate'),
            ],
        ];
    }

   /**
     * Store the current URL for the request if necessary.
     *
     * @see \Illuminate\Session\Middleware\StartSession::storeCurrentUrl()
     */
    protected function storeCurrentUrl(Request $request): void
    {
        if ($request->isMethod('GET') &&
            $request->route() instanceof Route &&
            ! ($request->ajax() && ! $request->inertia()) &&
            ! $request->prefetch() &&
            ! $request->isPrecognitive()) {
            session()->setPreviousUrl($request->fullUrl());
        }
    }
}
