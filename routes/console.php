<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
use Illuminate\Support\Facades\Schedule;

Schedule::command('cloudflare:reload')->daily();
Schedule::command('app:remove-accounts', ['--force'])->hourly();
