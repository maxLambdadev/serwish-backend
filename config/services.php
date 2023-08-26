<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'facebook' => [
        'client_id' => '2893342550966212',
        'client_secret' => 'a502359574adb610a918c4d778b1b9a4',
        'redirect' => 'https://api.serwish.ge/api/auth/facebook/callback'
    ],
    // 'facebook' => [
    //     'client_id' => '1231210724264182',
    //     'client_secret' => 'b2fea3b46ed8dbad453eb5a111e83ab5',
    //     'redirect' => 'https://api.serwish.ge/api/auth/facebook/callback'
    // ],
    'google' => [
        'client_id' => '664688263844-dhs3jahd43lusvsjo7jvgu4kroepbteu.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://api.serwish.ge/api/auth/google/callback'
    ],
    
];
