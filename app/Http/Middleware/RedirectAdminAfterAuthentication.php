<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminAfterAuthentication
{
    /**
     * Các route Login / Register hiện tại
     * trước đây điều hướng ADMIN về staff.dashboard.
     *
     * Middleware này chỉ áp dụng cho nhóm route auth.
     * Sau khi controller xử lý xong:
     *
     * - nếu chưa đăng nhập -> giữ nguyên response;
     * - nếu đã đăng nhập và là ADMIN
     *   -> đưa về admin.dashboard;
     * - các role khác không bị ảnh hưởng.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $response =
            $next($request);


        $user =
            $request->user();


        if (!$user) {
            return $response;
        }


        $user->loadMissing(
            'role'
        );


        if (
            $user->role?->code
            === 'ADMIN'
        ) {
            return redirect()
                ->route(
                    'admin.dashboard'
                );
        }


        return $response;
    }
}