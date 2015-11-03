<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => Coder\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        //account  : vuonghuuphuc
        'client_id' => '1452271165070444',
        'client_secret' => '654269284b8a70d32aa7b9ee327e7603',
        'redirect' => 'http://localhost/myphp/coder/public/social-callback/facebook',
    ],

    'google' => [
        //account  : vuonghuuphuc@gmail.com
        'client_id' => '108929664951-980q4ehntc5dldm5q33d4b0k1rshj2cl.apps.googleusercontent.com',
        'client_secret' => 'vdt0en-tR2MjpmSWkKKxnhq_',
        'redirect' => 'http://localhost/myphp/coder/public/social-callback/google',
    ],

    'linkedin' => [
        //account  : vuonghuuphuc@gmail.com
        'client_id' => '75b9zl5hps3hm1',
        'client_secret' => 'IOBqCqpSG6L4rIVD',
        'redirect' => 'http://localhost/myphp/coder/public/social-callback/linkedin',
    ],
];
