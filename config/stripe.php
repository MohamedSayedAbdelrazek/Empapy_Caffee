<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Stripe API Keys
    |--------------------------------------------------------------------------
    |
    | The Stripe publishable key and secret key give you access to Stripe's
    | API. The "publishable" key is typically used client-side and isn't
    | secret. The "secret" key should be kept confidential.
    |
    */

    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Webhook Secret
    |--------------------------------------------------------------------------
    |
    | This is the webhook signing secret used to verify that webhook events
    | are genuinely sent by Stripe and not by a third party.
    |
    */

    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | This is the default currency for payments. Stripe supports many currencies.
    | For Egypt, we use 'egp' (Egyptian Pound).
    |
    */

    'currency' => env('STRIPE_CURRENCY', 'egp'),
];
