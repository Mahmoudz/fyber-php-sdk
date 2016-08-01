<?php

return [

    'api_key' => env('FYBER_KEY', ''),

    'android_app_id' => env('ANDROID_APP_ID', ''),
    'ios_app_id'     => env('IOS_APP_ID', ''),
    'web_app_id'     => env('WEB_APP_ID', ''),

    'base_url'        => 'http://api.fyber.com/feed/',
    'api_version'     => '1',
    'response_format' => 'json',

    'offer_callback_token' => env('FYBER_OFFER_CALLBACK_TOKEN', ''),

];
