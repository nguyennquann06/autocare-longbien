<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Các role mà ADMIN được phép
     * gán từ giao diện quản trị MVP.
     *
     * Không cho tạo thêm ADMIN
     * trực tiếp từ chức năng này.
     */
    private const ASSIGNABLE_ROLES = [
        'CUSTOMER',
        'STAFF',
        'TECHNICIAN',
    ];


    /**
     * Danh sách tài khoản toàn hệ thống.
     */
    public function index(
        Request $request
    ): View {
        /*
        |--------------------------------------------------------------------------
        | NORMALIZE FILTERS
        |--------------------------------------------------------------------------
        */

        $keyword =
            preg_replace(
                '/\s+/u',
                ' ',
                trim(
                    (string)
                    $request->query(
                        'q',
                        ''
                    )
                )
            );


        $roleCode =
            strtoupper(
                trim(
                    (string)
                    $request->query(
                        'role',
                        ''
                    )
                )
            );


        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        $roles =
            Role::query()
                ->orderBy(
                    'id'
                )
                ->get([
                    'id',
                    'name',
                    'code',
                ]);


        $validRoleCodes =
            $roles
                ->pluck(
                    'code'
                )
                ->map(
                    fn ($code) =>
                        strtoupper(
                            (string)
                            $code
                        )
                )
                ->all();


        if (
            $roleCode !== ''
            &&
            !in_array(
                $roleCode,
                $validRoleCodes,
                true
            )
        ) {
            $roleCode =
                '';
        }


        /*
        |--------------------------------------------------------------------------
        | USER QUERY
        |--------------------------------------------------------------------------
        */

        $usersQuery =
            User::query()
                ->with([
                    'role',
                    'customer',
                ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($keyword !== '') {
            $usersQuery->where(
                function ($query) use (
                    $keyword
                ) {
                    $query
                        ->where(
                            'name',
                            'like',
                            '%'.$keyword.'%'
                        )
                        ->orWhere(
                            'email',
                            'like',
                            '%'.$keyword.'%'
                        );


                    if (
                        ctype_digit(
                            $keyword
                        )
                    ) {
                        $query->orWhere(
                            'id',
                            (int)
                            $keyword
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROLE FILTER
        |--------------------------------------------------------------------------
        */

        if ($roleCode !== '') {
            $usersQuery
                ->whereHas(
                    'role',
                    function ($query) use (
                        $roleCode
                    ) {
                        $query->where(
                            'code',
                            $roleCode
                        );
                    }
                );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $users =
            $usersQuery
                ->orderByDesc(
                    'id'
                )
                ->paginate(
                    15
                )
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ROLE COUNTS
        |--------------------------------------------------------------------------
        */

        $roleCounts = [];


        foreach ($roles as $role) {
            $roleCounts[
                $role->code
            ] =
                User::query()
                    ->where(
                        'role_id',
                        $role->id
                    )
                    ->count();
        }


        $totalUsers =
            User::query()
                ->count();


        return view(
            'admin.users.index',
            compact(
                'users',
                'roles',
                'roleCounts',
                'totalUsers',
                'keyword',
                'roleCode'
            )
        );
    }


    /**
     * Xem chi tiết một tài khoản.
     */
    public function show(
        User $user
    ): View {
        /*
        |--------------------------------------------------------------------------
        | BASIC RELATIONS
        |--------------------------------------------------------------------------
        */

        $user->load([
            'role',

            'customer.vehicles.brand',

            'customer.vehicles.vehicleModel',
        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY COUNTS
        |--------------------------------------------------------------------------
        */

        $user->loadCount([
            'createdServiceOrders',

            'technicianServiceOrders',

            'inventoryTransactions',

            'createdInvoices',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER DATA
        |--------------------------------------------------------------------------
        */

        $customer =
            $user->customer;


        if ($customer) {
            $customer->loadCount([
                'vehicles',

                'appointments',

                'serviceOrders',

                'invoices',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ASSIGNABLE ROLES
        |--------------------------------------------------------------------------
        */

        $assignableRoles =
            Role::query()
                ->whereIn(
                    'code',
                    self::ASSIGNABLE_ROLES
                )
                ->orderBy(
                    'id'
                )
                ->get([
                    'id',
                    'name',
                    'code',
                ]);


        /*
        |--------------------------------------------------------------------------
        | ACTIVE TECHNICIAN WORK
        |--------------------------------------------------------------------------
        */

        $activeTechnicianOrders =
            $user
                ->technicianServiceOrders()
                ->whereIn(
                    'status',
                    [
                        'RECEIVED',
                        'IN_PROGRESS',
                    ]
                )
                ->count();


        return view(
            'admin.users.show',
            compact(
                'user',
                'customer',
                'assignableRoles',
                'activeTechnicianOrders'
            )
        );
    }


    /**
     * ADMIN thay đổi vai trò
     * của một tài khoản.
     */
    public function updateRole(
        Request $request,
        User $user
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | NORMALIZE INPUT
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'role' =>
                strtoupper(
                    trim(
                        (string)
                        $request->input(
                            'role',
                            ''
                        )
                    )
                ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate(
                [
                    'role' => [
                        'bail',
                        'required',
                        'string',

                        Rule::in(
                            self::ASSIGNABLE_ROLES
                        ),
                    ],
                ],
                [
                    'role.required' =>
                        'Vui lòng chọn vai trò.',

                    'role.string' =>
                        'Vai trò không hợp lệ.',

                    'role.in' =>
                        'Chỉ được phân quyền CUSTOMER, STAFF hoặc TECHNICIAN.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | PROTECT CURRENT ADMIN
        |--------------------------------------------------------------------------
        |
        | ADMIN đang đăng nhập không được
        | tự hạ quyền chính mình.
        |
        */

        if (
            auth()->id()
            === $user->id
        ) {
            throw ValidationException::withMessages([
                'role' =>
                    'Bạn không thể tự thay đổi vai trò của tài khoản ADMIN đang đăng nhập.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE IN TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $user,
                $validated
            ) {
                /*
                |--------------------------------------------------------------------------
                | LOCK USER
                |--------------------------------------------------------------------------
                */

                $lockedUser =
                    User::query()
                        ->with(
                            'role'
                        )
                        ->lockForUpdate()
                        ->findOrFail(
                            $user->id
                        );


                $currentRole =
                    $lockedUser
                        ->role
                        ?->code;


                /*
                |--------------------------------------------------------------------------
                | PROTECT ALL ADMIN ACCOUNTS
                |--------------------------------------------------------------------------
                |
                | Trong MVP:
                |
                | - Không hạ quyền ADMIN.
                | - Không thay role ADMIN.
                | - Không tạo ADMIN mới qua UI.
                |
                */

                if (
                    $currentRole
                    === 'ADMIN'
                ) {
                    throw ValidationException::withMessages([
                        'role' =>
                            'Không thể thay đổi vai trò của tài khoản ADMIN từ chức năng này.',
                    ]);
                }


                $newRoleCode =
                    $validated['role'];


                /*
                |--------------------------------------------------------------------------
                | SAME ROLE
                |--------------------------------------------------------------------------
                */

                if (
                    $currentRole
                    === $newRoleCode
                ) {
                    throw ValidationException::withMessages([
                        'role' =>
                            'Tài khoản đã có vai trò này.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | TECHNICIAN ACTIVE WORK GUARD
                |--------------------------------------------------------------------------
                |
                | Không được đổi TECHNICIAN sang role khác
                | nếu còn phiếu RECEIVED / IN_PROGRESS.
                |
                */

                if (
                    $currentRole
                    === 'TECHNICIAN'
                    &&
                    $newRoleCode
                    !== 'TECHNICIAN'
                ) {
                    $activeOrders =
                        $lockedUser
                            ->technicianServiceOrders()
                            ->whereIn(
                                'status',
                                [
                                    'RECEIVED',
                                    'IN_PROGRESS',
                                ]
                            )
                            ->lockForUpdate()
                            ->get([
                                'id',
                            ]);


                    if (
                        $activeOrders
                            ->isNotEmpty()
                    ) {
                        throw ValidationException::withMessages([
                            'role' =>
                                'Kỹ thuật viên đang có phiếu bảo dưỡng chưa hoàn thành. Vui lòng hoàn tất hoặc phân công lại công việc trước khi đổi vai trò.',
                        ]);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | TARGET ROLE
                |--------------------------------------------------------------------------
                */

                $newRole =
                    Role::query()
                        ->where(
                            'code',
                            $newRoleCode
                        )
                        ->first();


                if (!$newRole) {
                    throw ValidationException::withMessages([
                        'role' =>
                            'Vai trò được chọn không tồn tại trong hệ thống.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | CUSTOMER PROFILE
                |--------------------------------------------------------------------------
                |
                | Khi STAFF / TECHNICIAN
                | chuyển sang CUSTOMER:
                |
                | nếu chưa có Customer profile
                | thì tạo tự động.
                |
                | Khi CUSTOMER chuyển sang
                | STAFF / TECHNICIAN:
                |
                | KHÔNG xóa Customer profile.
                | KHÔNG xóa xe.
                | KHÔNG xóa lịch hẹn.
                | KHÔNG xóa lịch sử bảo dưỡng.
                | KHÔNG xóa hóa đơn.
                |
                */

                if (
                    $newRoleCode
                    === 'CUSTOMER'
                    &&
                    !$lockedUser
                        ->customer()
                        ->exists()
                ) {
                    $lockedUser
                        ->customer()
                        ->create([
                            'full_name' =>
                                $lockedUser->name,

                            'email' =>
                                $lockedUser->email,
                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | UPDATE ROLE
                |--------------------------------------------------------------------------
                */

                $lockedUser->update([
                    'role_id' =>
                        $newRole->id,
                ]);
            }
        );


        return redirect()
            ->route(
                'admin.users.show',
                $user->id
            )
            ->with(
                'success',
                'Đã cập nhật vai trò tài khoản thành công.'
            );
    }
}