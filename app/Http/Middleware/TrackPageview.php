<?php

namespace App\Http\Middleware;

use Pirsch\Http\Middleware\TrackPageview as Middleware;

class TrackPageview extends Middleware
{
    /**
     * The URIs that should be excluded from tracking.
     *
     * @var array<int,string>
     */
    protected array $except = [
        'sentry/*',
    ];
}
