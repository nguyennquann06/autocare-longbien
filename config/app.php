<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env(
        'APP_NAME',
        'Laravel'
    ),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    */

    'env' => env(
        'APP_ENV',
        'production'
    ),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    */

    'debug' => (bool) env(
        'APP_DEBUG',
        false
    ),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    */

    'url' => env(
        'APP_URL',
        'http://localhost'
    ),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | AutoCare Long Biên hoạt động tại Việt Nam.
    |
    | Toàn bộ xử lý thời gian của Laravel sử dụng múi giờ:
    |
    | Asia/Ho_Chi_Minh (UTC+07:00)
    |
    */

    'timezone' => env(
        'APP_TIMEZONE',
        'Asia/Ho_Chi_Minh'
    ),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    */

    'locale' => env(
        'APP_LOCALE',
        'en'
    ),

    'fallback_locale' => env(
        'APP_FALLBACK_LOCALE',
        'en'
    ),

    'faker_locale' => env(
        'APP_FAKER_LOCALE',
        'en_US'
    ),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    */

    'cipher' =>
        'AES-256-CBC',

    'key' =>
        env(
            'APP_KEY'
        ),

    'previous_keys' => [
        ...array_filter(
            explode(
                ',',
                (string) env(
                    'APP_PREVIOUS_KEYS',
                    ''
                )
            )
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    */

    'maintenance' => [

        'driver' =>
            env(
                'APP_MAINTENANCE_DRIVER',
                'file'
            ),

        'store' =>
            env(
                'APP_MAINTENANCE_STORE',
                'database'
            ),

    ],

];