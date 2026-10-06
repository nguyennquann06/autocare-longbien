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
     * Các vai trò nhân sự có thể
     * chuyển đổi qua lại.
     *
     * CUSTOMER và ADMIN là các
     * nhóm tài khoản được bảo vệ.
     */
    private const WORKFORCE_ROLES = [
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
        | WORKFORCE ROLES
        |--------------------------------------------------------------------------
        |
        | Chỉ STAFF và TECHNICIAN
        | được chuyển đổi qua lại.
        |
        */

        $assignableRoles =
            Role::query()
                ->whereIn(
                    'code',
                    self::WORKFORCE_ROLES
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
     * của tài khoản nhân sự.
     *
     * Chỉ cho phép:
     *
     * STAFF <-> TECHNICIAN
     *
     * Không cho phép:
     *
     * CUSTOMER -> bất kỳ role khác
     * ADMIN    -> bất kỳ role khác
     * STAFF    -> CUSTOMER / ADMIN
     * TECHNICIAN -> CUSTOMER / ADMIN
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
        |
        | Ngay từ đầu chỉ chấp nhận
        | STAFF hoặc TECHNICIAN.
        |
        */

        $validated =
            $request->validate(
                [
                    'role' => [
                        'bail',

                        'required',

                        'string',

                        Rule::in(
                            self::WORKFORCE_ROLES
                        ),
                    ],
                ],
                [
                    'role.required' =>
                        'Vui lòng chọn vai trò.',

                    'role.string' =>
                        'Vai trò không hợp lệ.',

                    'role.in' =>
                        'Chỉ có thể chuyển đổi giữa STAFF và TECHNICIAN.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
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
                    strtoupper(
                        (string)
                        $lockedUser
                            ->role
                            ?->code
                    );


                $newRoleCode =
                    $validated['role'];


                /*
                |--------------------------------------------------------------------------
                | PROTECT ADMIN
                |--------------------------------------------------------------------------
                */

                if (
                    $currentRole
                    === 'ADMIN'
                ) {
                    throw ValidationException::withMessages([
                        'role' =>
                            'Tài khoản ADMIN được bảo vệ và không thể thay đổi vai trò từ chức năng này.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | PROTECT CUSTOMER
                |--------------------------------------------------------------------------
                |
                | CUSTOMER có thể đang gắn với:
                |
                | - hồ sơ khách hàng;
                | - phương tiện;
                | - lịch hẹn;
                | - phiếu bảo dưỡng;
                | - hóa đơn;
                | - lịch sử bảo dưỡng.
                |
                | Vì vậy không biến tài khoản
                | CUSTOMER thành tài khoản nhân sự.
                |
                */

                if (
                    $currentRole
                    === 'CUSTOMER'
                ) {
                    throw ValidationException::withMessages([
                        'role' =>
                            'Tài khoản CUSTOMER được giữ riêng cho khách hàng và không thể chuyển thành STAFF hoặc TECHNICIAN.',
                    ]);
                }


                /*
                |--------------------------------------------------------------------------
                | INVALID CURRENT ROLE
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        $currentRole,
                        self::WORKFORCE_ROLES,
                        true
                    )
                ) {
                    throw ValidationException::withMessages([
                        'role' =>
                            'Vai trò hiện tại của tài khoản không hỗ trợ chuyển đổi.',
                    ]);
                }


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
                | TECHNICIAN không được chuyển
                | sang STAFF nếu còn phiếu:
                |
                | RECEIVED
                | IN_PROGRESS
                |
                */

                if (
                    $currentRole
                    === 'TECHNICIAN'
                    &&
                    $newRoleCode
                    === 'STAFF'
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
                                'Kỹ thuật viên đang có phiếu bảo dưỡng chưa hoàn thành. Vui lòng hoàn tất hoặc phân công lại công việc trước khi chuyển sang STAFF.',
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
                | UPDATE ROLE
                |--------------------------------------------------------------------------
                |
                | Không tạo Customer profile.
                | Không xóa Customer profile.
                | Không tác động xe/lịch sử/hóa đơn.
                |
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
                'Đã cập nhật vai trò nhân sự thành công.'
            );
    }
}