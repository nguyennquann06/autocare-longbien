<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng ký.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }


    /**
     * Đăng ký tài khoản CUSTOMER.
     */
    public function register(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | NORMALIZE INPUT
        |--------------------------------------------------------------------------
        |
        | - Họ tên: bỏ khoảng trắng thừa
        | - Email: trim + lowercase
        | - Tuyệt đối không trim password
        |
        */

        $normalizedName =
            preg_replace(
                '/\s+/u',
                ' ',
                trim(
                    (string)
                    $request->input(
                        'name',
                        ''
                    )
                )
            );


        $normalizedEmail =
            Str::lower(
                trim(
                    (string)
                    $request->input(
                        'email',
                        ''
                    )
                )
            );


        $request->merge([
            'name' =>
                $normalizedName,

            'email' =>
                $normalizedEmail,
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate(
                [
                    'name' => [
                        'required',
                        'string',
                        'min:2',
                        'max:100',
                        "regex:/^(?=.*\\pL)[\\pL\\pM .'-]+$/u",
                    ],

                    'email' => [
                        'required',
                        'string',
                        'email:rfc',
                        'max:254',
                        'unique:users,email',
                    ],

                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        'max:72',
                        'regex:/[A-Za-z]/',
                        'regex:/[0-9]/',
                        'confirmed',
                    ],

                    'password_confirmation' => [
                        'required',
                        'string',
                        'max:72',
                    ],
                ],
                [
                    /*
                    |--------------------------------------------------------------------------
                    | NAME
                    |--------------------------------------------------------------------------
                    */

                    'name.required' =>
                        'Vui lòng nhập họ và tên.',

                    'name.string' =>
                        'Họ và tên không hợp lệ.',

                    'name.min' =>
                        'Họ và tên phải có ít nhất 2 ký tự.',

                    'name.max' =>
                        'Họ và tên không được vượt quá 100 ký tự.',

                    'name.regex' =>
                        'Họ và tên chỉ được chứa chữ cái, khoảng trắng và một số ký tự tên hợp lệ.',


                    /*
                    |--------------------------------------------------------------------------
                    | EMAIL
                    |--------------------------------------------------------------------------
                    */

                    'email.required' =>
                        'Vui lòng nhập địa chỉ email.',

                    'email.string' =>
                        'Email không hợp lệ.',

                    'email.email' =>
                        'Email không đúng định dạng. Ví dụ: example@email.com.',

                    'email.max' =>
                        'Email không được vượt quá 254 ký tự.',

                    'email.unique' =>
                        'Email này đã được sử dụng. Vui lòng sử dụng email khác.',


                    /*
                    |--------------------------------------------------------------------------
                    | PASSWORD
                    |--------------------------------------------------------------------------
                    */

                    'password.required' =>
                        'Vui lòng nhập mật khẩu.',

                    'password.string' =>
                        'Mật khẩu không hợp lệ.',

                    'password.min' =>
                        'Mật khẩu phải có ít nhất 8 ký tự.',

                    'password.max' =>
                        'Mật khẩu không được vượt quá 72 ký tự.',

                    'password.regex' =>
                        'Mật khẩu phải có ít nhất một chữ cái và một chữ số.',

                    'password.confirmed' =>
                        'Mật khẩu nhập lại không khớp.',


                    /*
                    |--------------------------------------------------------------------------
                    | PASSWORD CONFIRMATION
                    |--------------------------------------------------------------------------
                    */

                    'password_confirmation.required' =>
                        'Vui lòng nhập lại mật khẩu.',

                    'password_confirmation.string' =>
                        'Mật khẩu nhập lại không hợp lệ.',

                    'password_confirmation.max' =>
                        'Mật khẩu nhập lại không được vượt quá 72 ký tự.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER ROLE
        |--------------------------------------------------------------------------
        */

        $customerRole =
            Role::where(
                'code',
                'CUSTOMER'
            )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | CREATE ACCOUNT
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $validated,
                $customerRole
            ) {
                $user =
                    User::create([
                        'role_id' =>
                            $customerRole->id,

                        'name' =>
                            $validated['name'],

                        'email' =>
                            $validated['email'],

                        'password' =>
                            $validated['password'],
                    ]);


                $user
                    ->customer()
                    ->create([
                        'full_name' =>
                            $validated['name'],

                        'email' =>
                            $validated['email'],
                    ]);
            }
        );


        return redirect()
            ->route('login')
            ->with(
                'success',
                'Đăng ký tài khoản thành công. Bạn có thể đăng nhập ngay.'
            );
    }


    /**
     * Hiển thị form đăng nhập.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }


    /**
     * Xử lý đăng nhập.
     */
    public function login(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | NORMALIZE EMAIL
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'email' =>
                Str::lower(
                    trim(
                        (string)
                        $request->input(
                            'email',
                            ''
                        )
                    )
                ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        |
        | Không áp dụng min:8 khi login
        | vì hệ thống có thể còn tài khoản cũ
        | được tạo với mật khẩu ngắn hơn.
        |
        */

        $credentials =
            $request->validate(
                [
                    'email' => [
                        'required',
                        'string',
                        'email:rfc',
                        'max:254',
                    ],

                    'password' => [
                        'required',
                        'string',
                        'max:72',
                    ],
                ],
                [
                    'email.required' =>
                        'Vui lòng nhập địa chỉ email.',

                    'email.string' =>
                        'Email không hợp lệ.',

                    'email.email' =>
                        'Email không đúng định dạng. Ví dụ: example@email.com.',

                    'email.max' =>
                        'Email không được vượt quá 254 ký tự.',

                    'password.required' =>
                        'Vui lòng nhập mật khẩu.',

                    'password.string' =>
                        'Mật khẩu không hợp lệ.',

                    'password.max' =>
                        'Mật khẩu không được vượt quá 72 ký tự.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATE
        |--------------------------------------------------------------------------
        */

        if (
            Auth::attempt(
                $credentials,
                $request->boolean(
                    'remember'
                )
            )
        ) {
            /*
             * Chống session fixation.
             */
            $request
                ->session()
                ->regenerate();


            $user =
                Auth::user();


            $user->load(
                'role'
            );


            /*
            |--------------------------------------------------------------------------
            | USER CHƯA CÓ ROLE
            |--------------------------------------------------------------------------
            */

            if (!$user->role) {
                Auth::logout();


                $request
                    ->session()
                    ->invalidate();


                $request
                    ->session()
                    ->regenerateToken();


                return back()
                    ->withErrors([
                        'email' =>
                            'Tài khoản chưa được phân quyền. Vui lòng liên hệ quản trị viên.',
                    ])
                    ->onlyInput(
                        'email'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | CUSTOMER
            |--------------------------------------------------------------------------
            */

            if (
                $user->role->code
                === 'CUSTOMER'
            ) {
                $intendedUrl =
                    $request
                        ->session()
                        ->pull(
                            'url.intended'
                        );


                if (
                    $intendedUrl
                    &&
                    str_contains(
                        $intendedUrl,
                        '/appointments/create'
                    )
                ) {
                    return redirect(
                        $intendedUrl
                    )->with(
                        'success',
                        'Đăng nhập thành công.'
                    );
                }


                return redirect()
                    ->route(
                        'customer.dashboard'
                    )
                    ->with(
                        'success',
                        'Đăng nhập thành công.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | STAFF
            |--------------------------------------------------------------------------
            */

            if (
                $user->role->code
                === 'STAFF'
            ) {
                $request
                    ->session()
                    ->forget(
                        'url.intended'
                    );


                return redirect()
                    ->route(
                        'staff.dashboard'
                    )
                    ->with(
                        'success',
                        'Đăng nhập nhân viên thành công.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | ADMIN
            |--------------------------------------------------------------------------
            */

            if (
                $user->role->code
                === 'ADMIN'
            ) {
                $request
                    ->session()
                    ->forget(
                        'url.intended'
                    );


                return redirect()
                    ->route(
                        'staff.dashboard'
                    )
                    ->with(
                        'success',
                        'Đăng nhập quản trị thành công.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | TECHNICIAN
            |--------------------------------------------------------------------------
            */

            if (
                $user->role->code
                === 'TECHNICIAN'
            ) {
                $request
                    ->session()
                    ->forget(
                        'url.intended'
                    );


                return redirect()
                    ->route(
                        'technician.service-orders.index'
                    )
                    ->with(
                        'success',
                        'Đăng nhập kỹ thuật viên thành công.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | ROLE KHÔNG HỢP LỆ
            |--------------------------------------------------------------------------
            */

            Auth::logout();


            $request
                ->session()
                ->invalidate();


            $request
                ->session()
                ->regenerateToken();


            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                        'Vai trò tài khoản không hợp lệ.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | WRONG CREDENTIALS
        |--------------------------------------------------------------------------
        */

        return back()
            ->withErrors([
                'email' =>
                    'Email hoặc mật khẩu không chính xác.',
            ])
            ->onlyInput(
                'email'
            );
    }


    /**
     * Đăng xuất tài khoản.
     */
    public function logout(Request $request)
    {
        Auth::logout();


        $request
            ->session()
            ->invalidate();


        $request
            ->session()
            ->regenerateToken();


        return redirect()
            ->route('home')
            ->with(
                'success',
                'Bạn đã đăng xuất thành công.'
            );
    }
}