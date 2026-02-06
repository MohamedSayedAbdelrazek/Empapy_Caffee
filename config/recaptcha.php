<?php

return [

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Site Key
    |--------------------------------------------------------------------------
    |
    | The site key for Google reCAPTCHA v3. Get it from:
    | https://www.google.com/recaptcha/admin/create
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Secret Key
    |--------------------------------------------------------------------------
    |
    | The secret key for Google reCAPTCHA v3. Keep this private!
    |
    */

    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Minimum Score
    |--------------------------------------------------------------------------
    |
    | The minimum score required to pass reCAPTCHA verification.
    | Score ranges from 0.0 to 1.0, where 1.0 is very likely a good
    | interaction and 0.0 is very likely a bot.
    |
    */

    'min_score' => env('RECAPTCHA_MIN_SCORE', 0.5),

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Enable or disable reCAPTCHA verification globally.
    | Set to false in development if you don't have keys.
    |
    */

    'enabled' => env('RECAPTCHA_ENABLED', true),

];
