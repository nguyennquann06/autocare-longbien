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
     * Hiển thị form đăng ký.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }


    /**
     * Xử lý đăng ký tài khoản khách hàng.
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


        /**
         * Người dùng đăng ký trên website
         * mặc định là CUSTOMER.
         */
        $customerRole = Role::where(
            'code',
            'CUSTOMER'
        )->firstOrFail();


        /**
         * Tạo tài khoản + hồ sơ khách hàng.
         */
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


        /**
         * Xử lý đăng nhập.
         */
        if (
            Auth::attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {
            /**
             * Tạo session mới sau đăng nhập.
             */
            $request->session()->regenerate();


            $user = Auth::user();


            /**
             * Load role của tài khoản.
             */
            $user->load('role');


            /**
             * Nếu tài khoản không có role.
             */
            if (!$user->role) {
                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return back()
                    ->withErrors([
                        'email' =>
                            'Tài khoản chưa được phân quyền.',
                    ])
                    ->onlyInput('email');
            }


            /**
             * ==============================
             * CUSTOMER
             * ==============================
             */
            if (
                $user->role->code === 'CUSTOMER'
            ) {
                /**
                 * Lấy URL mà khách định truy cập
                 * trước khi bị chuyển sang login.
                 */
                $intendedUrl = $request
                    ->session()
                    ->pull('url.intended');


                /**
                 * Chỉ cho CUSTOMER quay lại
                 * các URL hợp lệ dành cho khách.
                 *
                 * Hiện tại trường hợp quan trọng nhất
                 * là form đặt lịch.
                 */
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


                /**
                 * Mặc định CUSTOMER
                 * về trang Xe của tôi.
                 */
                return redirect()
                    ->route('vehicles.index')
                    ->with(
                        'success',
                        'Đăng nhập thành công.'
                    );
            }


            /**
             * ==============================
             * STAFF
             * ==============================
             */
            if (
                $user->role->code === 'STAFF'
            ) {
                /**
                 * Xóa intended URL cũ để STAFF
                 * không bị đưa vào route CUSTOMER.
                 */
                $request
                    ->session()
                    ->forget('url.intended');


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
             * ==============================
             * ADMIN
             * ==============================
             *
             * Hiện chưa có Admin Dashboard riêng,
             * nên tạm đưa Admin vào trang quản lý
             * lịch hẹn giống STAFF.
             */
            if (
                $user->role->code === 'ADMIN'
            ) {
                $request
                    ->session()
                    ->forget('url.intended');


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
             * ==============================
             * TECHNICIAN
             * ==============================
             *
             * Chưa xây dựng giao diện riêng.
             */
            if (
                $user->role->code === 'TECHNICIAN'
            ) {
                $request
                    ->session()
                    ->forget('url.intended');


                return redirect()
                    ->route('home')
                    ->with(
                        'success',
                        'Đăng nhập thành công.'
                    );
            }


            /**
             * Trường hợp role không xác định.
             */
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();


            return redirect()
                ->route('login')
                ->withErrors([
                    'email' =>
                        'Vai trò tài khoản không hợp lệ.',
                ]);
        }


        /**
         * Sai email hoặc mật khẩu.
         */
        return back()
            ->withErrors([
                'email' =>
                    'Email hoặc mật khẩu không chính xác.',
            ])
            ->onlyInput('email');
    }
}