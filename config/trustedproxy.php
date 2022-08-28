<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Trusted proxy
    |--------------------------------------------------------------------------
    |
    | Set trusted proxy IP addresses.
    | The "*" character is syntactic sugar within TrustedProxy to trust any
    | proxy that connects directly to your server, a requirement when you
    | cannot know the address of your proxy (e.g. if using ELB or similar).
    |
    */

    'proxies' => env('TRUSTED_PROXIES'),

];
