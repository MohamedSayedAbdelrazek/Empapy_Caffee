<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Project Configuration
    |--------------------------------------------------------------------------
    */
    
    'credentials' => [
        'file' => storage_path('app/firebase/service-account.json'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging (FCM)
    |--------------------------------------------------------------------------
    */
    
    'fcm' => [
        // VAPID Key for Web Push
        'vapid_key' => env('FIREBASE_VAPID_KEY', 'BB_6B4n5vvVhVvzJlQSJhtVDgCZ-5BMHQR_vaZsJt3862E59iG5NWBncya4kqNeG7suv-d-gFn6zSo79ne3IzJI'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Web App Configuration (for frontend)
    |--------------------------------------------------------------------------
    */
    
    'web' => [
        'api_key' => env('FIREBASE_API_KEY', 'AIzaSyC9xBlrJOtsMPWgGwJnMLmuVkYiCDCJF_M'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'empapy-caffe.firebaseapp.com'),
        'project_id' => env('FIREBASE_PROJECT_ID', 'empapy-caffe'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'empapy-caffe.firebasestorage.app'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', '345834961954'),
        'app_id' => env('FIREBASE_APP_ID', '1:345834961954:web:d9e1c1df8e54be93935e7b'),
        'measurement_id' => env('FIREBASE_MEASUREMENT_ID', 'G-47B8S82J14'),
    ],
];
