<?php

return [

    /*
    |--------------------------------------------------------------------------
    | R2 Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for R2 storage. You can access these
    | settings using the config() helper throughout your application.
    |
    */

    'bucket' => env('R2_BUCKET', 'number-r1-storage'),
    'endpoint' => env('R2_ENDPOINT', 'https://r2-storage.number.app.br'),
    'cdn' => env('R2_CDN', 'https://cdn.number.app.br/'),
];
