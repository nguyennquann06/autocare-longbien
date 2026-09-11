<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Form đăng ký.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }


    /**
     * Đăng ký CUSTOMER.
     */
    public function register(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'confirmed',
                ],
            ],
            [
                'name.required' =>
                    'Vui lòng nhập họ và tên.',

                'email.required' =>
                    'Vui lòng nhập email.',

                'email.email' =>
                    'Email không đúng định dạng.',

                'email.unique' =>
                    'Email này đã được sử dụng.',

                'password.required' =>
                    'Vui lòng nhập mật khẩu.',

                'password.min' =>
                    'Mật khẩu phải có ít nhất 6 ký tự.',

                'password.confirmed' =>
                    'Mật khẩu nhập lại không khớp.',
            ]
        );


        $customerRole = Role::where(
            'code',
            'CUSTOMER'
        )->firstOrFail();


        DB::transaction(
            function () use (
                $validated,
                $customerRole
            ) {
                $user = User::create([
                    'role_id' =>
                        $customerRole->id,

                    'name' =>
                        $validated['name'],

                    'email' =>
                        $validated['email'],

                    'password' =>
                        $validated['password'],
                ]);


                $user->customer()->create([
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
                'Đăng ký tài khoản thành công. Vui lòng đăng nhập.'
            );
    }


    /**
     * Form đăng nhập.
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
        $credentials = $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                ],

                'password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'email.required' =>
                    'Vui lòng nhập email.',

                'email.email' =>
                    'Email không đúng định dạng.',

                'password.required' =>
                    'Vui lòng nhập mật khẩu.',
            ]
        );


        if (
            Auth::attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {
            $request
                ->session()
                ->regenerate();


            $user = Auth::user();

            $user->load('role');


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
                            'Tài khoản chưa được phân quyền.',
                    ])
                    ->onlyInput('email');
            }


            /**
             * CUSTOMER
             */
            if (
                $user->role->code ===
                'CUSTOMER'
            ) {
                $intendedUrl =
                    $request
                        ->session()
                        ->pull(
                            'url.intended'
                        );


                if (
                    $intendedUrl &&
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
                        'vehicles.index'
                    )
                    ->with(
                        'success',
                        'Đăng nhập thành công.'
                    );
            }


            /**
             * STAFF
             */
            if (
                $user->role->code ===
                'STAFF'
            ) {
                $request
                    ->session()
                    ->forget(
                        'url.intended'
                    );


                return redirect()
                    ->route(
                        'staff.appointments.index'
                    )
                    ->with(
                        'success',
                        'Đăng nhập nhân viên thành công.'
                    );
            }


            /**
             * ADMIN
             *
             * Chưa có Dashboard riêng.
             */
            if (
                $user->role->code ===
                'ADMIN'
            ) {
                $request
                    ->session()
                    ->forget(
                        'url.intended'
                    );


                return redirect()
                    ->route(
                        'staff.appointments.index'
                    )
                    ->with(
                        'success',
                        'Đăng nhập quản trị thành công.'
                    );
            }


            /**
             * TECHNICIAN
             */
            if (
                $user->role->code ===
                'TECHNICIAN'
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


        return back()
            ->withErrors([
                'email' =>
                    'Email hoặc mật khẩu không chính xác.',
            ])
            ->onlyInput('email');
    }
}