<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng ký.
     *
     * User đã đăng nhập không được quay lại
     * trang đăng ký.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }

        return response()
            ->view('auth.register')
            ->withHeaders(
                $this->authPageNoCacheHeaders()
            );
    }


    /**
     * Đăng ký tài khoản CUSTOMER.
     */
    public function register(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }

        /*
        |--------------------------------------------------------------------------
        | NORMALIZE INPUT
        |--------------------------------------------------------------------------
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

                    'password_confirmation.required' =>
                        'Vui lòng nhập lại mật khẩu.',

                    'password_confirmation.string' =>
                        'Mật khẩu nhập lại không hợp lệ.',

                    'password_confirmation.max' =>
                        'Mật khẩu nhập lại không được vượt quá 72 ký tự.',
                ]
            );


        $customerRole =
            Role::where(
                'code',
                'CUSTOMER'
            )->firstOrFail();


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
     *
     * User đã đăng nhập không được render lại
     * trang login.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }

        return response()
            ->view('auth.login')
            ->withHeaders(
                $this->authPageNoCacheHeaders()
            );
    }


    /**
     * Đăng nhập email / mật khẩu.
     */
    public function login(Request $request)
    {
        /*
         * Không cho một session đang đăng nhập
         * thực hiện đăng nhập lần thứ hai.
         */
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }


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


        if (
            Auth::attempt(
                $credentials,
                $request->boolean(
                    'remember'
                )
            )
        ) {
            $request
                ->session()
                ->regenerate();


            $user =
                Auth::user();


            $user->load(
                'role'
            );


            if (!$user->role) {
                $this->logoutCurrentSession(
                    $request
                );

                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Tài khoản chưa được phân quyền. Vui lòng liên hệ quản trị viên.'
                    );
            }


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


            $this->logoutCurrentSession(
                $request
            );


            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Vai trò tài khoản không hợp lệ.'
                );
        }


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
     * Chuyển người dùng tới Google OAuth.
     */
    public function redirectToGoogle()
    {
        /*
         * Session đã đăng nhập thì không cho
         * khởi tạo một OAuth login mới.
         */
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }


        return Socialite::driver(
            'google'
        )->redirect();
    }


    /**
     * Xử lý callback Google OAuth.
     */
    public function handleGoogleCallback(
        Request $request
    ) {
        /*
         * Trường hợp callback bị truy cập lại bằng
         * Back / Refresh sau khi user đã đăng nhập.
         */
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }


        try {
            $googleUser =
                Socialite::driver(
                    'google'
                )->user();
        } catch (Throwable $exception) {
            report(
                $exception
            );


            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Không thể xác thực với Google. Vui lòng thử lại.'
                );
        }


        $googleId =
            trim(
                (string)
                $googleUser->getId()
            );


        $email =
            Str::lower(
                trim(
                    (string)
                    $googleUser->getEmail()
                )
            );


        if (
            $googleId === ''
            ||
            $email === ''
        ) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Google không cung cấp đủ thông tin tài khoản để đăng nhập.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | EMAIL VERIFICATION
        |--------------------------------------------------------------------------
        */

        $rawVerified =
            data_get(
                $googleUser->user,
                'email_verified',
                data_get(
                    $googleUser->user,
                    'verified_email'
                )
            );


        if (
            $rawVerified !== null
            &&
            filter_var(
                $rawVerified,
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            ) === false
        ) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Email Google chưa được xác minh nên chưa thể sử dụng để đăng nhập.'
                );
        }


        $googleName =
            $this->normalizeGoogleName(
                $googleUser->getName(),
                $email
            );


        try {
            $result =
                DB::transaction(
                    function () use (
                        $googleId,
                        $email,
                        $googleName
                    ) {
                        /*
                        |--------------------------------------------------------------------------
                        | GOOGLE ID ĐÃ ĐƯỢC LIÊN KẾT
                        |--------------------------------------------------------------------------
                        */

                        $userByGoogleId =
                            User::query()
                                ->where(
                                    'google_id',
                                    $googleId
                                )
                                ->lockForUpdate()
                                ->first();


                        if ($userByGoogleId) {
                            $userByGoogleId
                                ->load(
                                    'role'
                                );


                            if (
                                !$userByGoogleId->role
                                ||
                                $userByGoogleId
                                    ->role
                                    ->code
                                !== 'CUSTOMER'
                            ) {
                                return [
                                    'error' =>
                                        'Đăng nhập Google chỉ dành cho tài khoản khách hàng.',
                                ];
                            }


                            if (
                                !$userByGoogleId
                                    ->email_verified_at
                            ) {
                                $userByGoogleId
                                    ->forceFill([
                                        'email_verified_at' =>
                                            now(),
                                    ])
                                    ->save();
                            }


                            $this->ensureCustomerProfile(
                                $userByGoogleId,
                                $googleName,
                                $email
                            );


                            return [
                                'user' =>
                                    $userByGoogleId,
                            ];
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | EMAIL ĐÃ TỒN TẠI
                        |--------------------------------------------------------------------------
                        */

                        $userByEmail =
                            User::query()
                                ->where(
                                    'email',
                                    $email
                                )
                                ->lockForUpdate()
                                ->first();


                        if ($userByEmail) {
                            $userByEmail
                                ->load(
                                    'role'
                                );


                            if (
                                !$userByEmail->role
                                ||
                                $userByEmail
                                    ->role
                                    ->code
                                !== 'CUSTOMER'
                            ) {
                                return [
                                    'error' =>
                                        'Email này thuộc tài khoản nhân viên hoặc kỹ thuật viên. Vui lòng đăng nhập bằng mật khẩu.',
                                ];
                            }


                            if (
                                $userByEmail->google_id
                                &&
                                $userByEmail->google_id
                                !== $googleId
                            ) {
                                return [
                                    'error' =>
                                        'Email này đã được liên kết với một tài khoản Google khác.',
                                ];
                            }


                            $userByEmail
                                ->forceFill([
                                    'google_id' =>
                                        $googleId,

                                    'email_verified_at' =>
                                        $userByEmail
                                            ->email_verified_at
                                        ?? now(),
                                ])
                                ->save();


                            $this->ensureCustomerProfile(
                                $userByEmail,
                                $googleName,
                                $email
                            );


                            return [
                                'user' =>
                                    $userByEmail,
                            ];
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | GOOGLE ACCOUNT MỚI
                        |--------------------------------------------------------------------------
                        */

                        $customerRole =
                            Role::query()
                                ->where(
                                    'code',
                                    'CUSTOMER'
                                )
                                ->first();


                        if (!$customerRole) {
                            return [
                                'error' =>
                                    'Hệ thống chưa cấu hình vai trò khách hàng.',
                            ];
                        }


                        $newUser =
                            User::create([
                                'role_id' =>
                                    $customerRole->id,

                                'name' =>
                                    $googleName,

                                'email' =>
                                    $email,

                                'email_verified_at' =>
                                    now(),

                                'google_id' =>
                                    $googleId,

                                /*
                                 * Google Login không dùng
                                 * mật khẩu này.
                                 */
                                'password' =>
                                    Str::random(
                                        64
                                    ),
                            ]);


                        $newUser
                            ->customer()
                            ->create([
                                'full_name' =>
                                    $googleName,

                                'email' =>
                                    $email,
                            ]);


                        $newUser->load(
                            'role'
                        );


                        return [
                            'user' =>
                                $newUser,
                        ];
                    }
                );
        } catch (Throwable $exception) {
            report(
                $exception
            );


            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Không thể hoàn tất đăng nhập Google. Vui lòng thử lại.'
                );
        }


        if (
            isset(
                $result['error']
            )
        ) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    $result['error']
                );
        }


        $user =
            $result['user']
            ?? null;


        if (!$user) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Không thể xác định tài khoản khách hàng.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN
        |--------------------------------------------------------------------------
        */

        Auth::login(
            $user
        );


        $request
            ->session()
            ->regenerate();


        /*
        |--------------------------------------------------------------------------
        | INTENDED APPOINTMENT URL
        |--------------------------------------------------------------------------
        */

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
                'Đăng nhập bằng Google thành công.'
            );
        }


        return redirect()
            ->route(
                'customer.dashboard'
            )
            ->with(
                'success',
                'Đăng nhập bằng Google thành công.'
            );
    }


    /**
     * Đăng xuất.
     */
    public function logout(Request $request)
    {
        $this->logoutCurrentSession(
            $request
        );


        return redirect()
            ->route('home')
            ->with(
                'success',
                'Bạn đã đăng xuất thành công.'
            );
    }


    /**
     * Điều hướng một user đã đăng nhập
     * về đúng khu vực theo role.
     *
     * Đây là lớp bảo vệ cho trường hợp:
     * - bấm Back về /login;
     * - refresh /login khi session còn hiệu lực;
     * - truy cập trực tiếp /register;
     * - truy cập lại Google callback.
     */
    private function redirectAuthenticatedUser()
    {
        $user =
            Auth::user();


        if (!$user) {
            return redirect()
                ->route('login');
        }


        $user->loadMissing(
            'role'
        );


        $roleCode =
            $user->role?->code;


        if (
            $roleCode
            === 'CUSTOMER'
        ) {
            return redirect()
                ->route(
                    'customer.dashboard'
                );
        }


        if (
            $roleCode
            === 'STAFF'
            ||
            $roleCode
            === 'ADMIN'
        ) {
            return redirect()
                ->route(
                    'staff.dashboard'
                );
        }


        if (
            $roleCode
            === 'TECHNICIAN'
        ) {
            return redirect()
                ->route(
                    'technician.service-orders.index'
                );
        }


        /*
         * Session không hợp lệ:
         * không giữ user không có role hợp lệ.
         */
        $request =
            request();


        $this->logoutCurrentSession(
            $request
        );


        return redirect()
            ->route('login')
            ->with(
                'error',
                'Vai trò tài khoản không hợp lệ.'
            );
    }


    /**
     * Logout + hủy session hiện tại.
     */
    private function logoutCurrentSession(
        Request $request
    ): void {
        Auth::logout();


        $request
            ->session()
            ->invalidate();


        $request
            ->session()
            ->regenerateToken();
    }


    /**
     * Header chống browser cache cho
     * trang Login / Register.
     */
    private function authPageNoCacheHeaders(): array
    {
        return [
            'Cache-Control' =>
                'no-store, no-cache, must-revalidate, max-age=0',

            'Pragma' =>
                'no-cache',

            'Expires' =>
                '0',
        ];
    }


    /**
     * Chuẩn hóa tên lấy từ Google.
     */
    private function normalizeGoogleName(
        mixed $name,
        string $email
    ): string {
        $normalized =
            preg_replace(
                '/\s+/u',
                ' ',
                trim(
                    (string)
                    $name
                )
            );


        if (
            !$normalized
            ||
            trim(
                $normalized
            ) === ''
        ) {
            $normalized =
                Str::before(
                    $email,
                    '@'
                );
        }


        return Str::substr(
            $normalized,
            0,
            100
        );
    }


    /**
     * Đảm bảo CUSTOMER có hồ sơ customer.
     */
    private function ensureCustomerProfile(
        User $user,
        string $name,
        string $email
    ): void {
        if (
            $user
                ->customer()
                ->exists()
        ) {
            return;
        }


        $user
            ->customer()
            ->create([
                'full_name' =>
                    $name,

                'email' =>
                    $email,
            ]);
    }
}