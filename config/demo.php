<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo Admin Account
    |--------------------------------------------------------------------------
    |
    | Tài khoản ADMIN đầu tiên khi deploy demo.
    | Mật khẩu chỉ được cấu hình qua biến môi trường.
    |
    */

    'admin' => [
        'name' => env(
            'DEMO_ADMIN_NAME',
            'Quản trị viên'
        ),

        'email' => env(
            'DEMO_ADMIN_EMAIL'
        ),

        'password' => env(
            'DEMO_ADMIN_PASSWORD'
        ),
    ],

];
