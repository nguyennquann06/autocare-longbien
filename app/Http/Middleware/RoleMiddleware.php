<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Kiểm tra role của user trước khi
     * cho phép request đi tiếp.
     *
     * Ví dụ:
     *
     * role:CUSTOMER
     * role:STAFF,ADMIN
     * role:TECHNICIAN
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$allowedRoles
    ): Response {
        $user =
            $request->user();


        /*
        |--------------------------------------------------------------------------
        | AUTHENTICATION
        |--------------------------------------------------------------------------
        |
        | Thông thường middleware auth chạy trước role.
        | Tuy nhiên vẫn kiểm tra lại để tránh middleware
        | được sử dụng sai ở route khác trong tương lai.
        |
        */

        if (!$user) {
            return redirect()
                ->route('login');
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD ROLE
        |--------------------------------------------------------------------------
        */

        $user->loadMissing(
            'role'
        );


        $roleCode =
            $user->role?->code;


        /*
        |--------------------------------------------------------------------------
        | INVALID ROLE
        |--------------------------------------------------------------------------
        */

        if (!$roleCode) {
            abort(
                403,
                'Tài khoản chưa được phân quyền hợp lệ.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE ALLOWED ROLES
        |--------------------------------------------------------------------------
        */

        $normalizedAllowedRoles =
            array_map(
                static fn (string $role): string =>
                    strtoupper(
                        trim($role)
                    ),
                $allowedRoles
            );


        /*
        |--------------------------------------------------------------------------
        | AUTHORIZATION
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                strtoupper($roleCode),
                $normalizedAllowedRoles,
                true
            )
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập chức năng này.'
            );
        }


        return $next(
            $request
        );
    }
}