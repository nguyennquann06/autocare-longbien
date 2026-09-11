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
                'name.required' => 'Vui lòng nhập họ và tên.',

                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email này đã được sử dụng.',

                'password.required' => 'Vui lòng nhập mật khẩu.',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'password.confirmed' => 'Mật khẩu nhập lại không khớp.',
            ]
        );

        /**
         * Lấy role CUSTOMER.
         */
        $customerRole = Role::where('code', 'CUSTOMER')
            ->firstOrFail();

        /**
         * Dùng transaction để đảm bảo:
         *
         * - Tạo User thành công
         * - Tạo Customer thành công
         *
         * Nếu một trong hai lỗi thì rollback toàn bộ.
         */
        DB::transaction(function () use ($validated, $customerRole) {

            /**
             * Tạo tài khoản.
             */
            $user = User::create([
                'role_id' => $customerRole->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);

            /**
             * Tự động tạo hồ sơ khách hàng.
             */
            $user->customer()->create([
                'full_name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        });

        /**
         * Đăng ký thành công
         * → chuyển đến đăng nhập.
         */
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
        /**
         * Validate dữ liệu đăng nhập.
         */
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
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không đúng định dạng.',

                'password.required' => 'Vui lòng nhập mật khẩu.',
            ]
        );

        /**
         * Kiểm tra email và mật khẩu.
         */
        if (Auth::attempt($credentials)) {

            /**
             * Regenerate session để tránh Session Fixation.
             */
            $request->session()->regenerate();

            /**
             * Lấy người dùng hiện tại.
             */
            $user = Auth::user();

            /**
             * CUSTOMER
             * → chuyển đến trang "Xe của tôi".
             */
            if (
                $user->role &&
                $user->role->code === 'CUSTOMER'
            ) {
                return redirect()
                    ->route('vehicles.index')
                    ->with(
                        'success',
                        'Đăng nhập thành công.'
                    );
            }

            /**
             * Các role khác:
             *
             * ADMIN
             * STAFF
             * TECHNICIAN
             *
             * Hiện tại tạm thời về trang chủ.
             * Sau này sẽ chuyển sang Dashboard tương ứng.
             */
            return redirect()
                ->route('home')
                ->with(
                    'success',
                    'Đăng nhập thành công.'
                );
        }

        /**
         * Sai email hoặc mật khẩu.
         */
        return back()
            ->withErrors([
                'email' => 'Email hoặc mật khẩu không chính xác.',
            ])
            ->onlyInput('email');
    }
}