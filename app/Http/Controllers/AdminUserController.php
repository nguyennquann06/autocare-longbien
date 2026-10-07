<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Các vai trò nhân sự trong garage.
     *
     * CUSTOMER và ADMIN không được
     * tạo/chuyển đổi qua chức năng này.
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
     * Form tạo tài khoản nhân sự.
     */
    public function create(): View
    {
        $workforceRoles =
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
                    'description',
                ]);


        return view(
            'admin.users.create',
            compact(
                'workforceRoles'
            )
        );
    }


    /**
     * Tạo tài khoản STAFF / TECHNICIAN.
     */
    public function store(
        Request $request
    ): RedirectResponse {
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


        $normalizedRole =
            strtoupper(
                trim(
                    (string)
                    $request->input(
                        'role',
                        ''
                    )
                )
            );


        $request->merge([
            'name' =>
                $normalizedName,

            'email' =>
                $normalizedEmail,

            'role' =>
                $normalizedRole,
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

                    'role' => [
                        'required',
                        'string',

                        Rule::in(
                            self::WORKFORCE_ROLES
                        ),
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
                        'Email không đúng định dạng.',

                    'email.max' =>
                        'Email không được vượt quá 254 ký tự.',

                    'email.unique' =>
                        'Email này đã được sử dụng.',

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

                    'role.required' =>
                        'Vui lòng chọn vai trò nhân sự.',

                    'role.string' =>
                        'Vai trò không hợp lệ.',

                    'role.in' =>
                        'Chỉ được tạo tài khoản STAFF hoặc TECHNICIAN.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | AUTHORITATIVE ROLE LOOKUP
        |--------------------------------------------------------------------------
        */

        $role =
            Role::query()
                ->where(
                    'code',
                    $validated['role']
                )
                ->whereIn(
                    'code',
                    self::WORKFORCE_ROLES
                )
                ->first();


        if (!$role) {
            throw ValidationException::withMessages([
                'role' =>
                    'Vai trò nhân sự được chọn không tồn tại trong hệ thống.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE USER + ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        $user =
            DB::transaction(
                function () use (
                    $validated,
                    $role
                ) {
                    $user =
                        User::query()
                            ->create([
                                'role_id' =>
                                    $role->id,

                                'name' =>
                                    $validated['name'],

                                'email' =>
                                    $validated['email'],

                                /*
                                 * User model sử dụng
                                 * password cast "hashed".
                                 */
                                'password' =>
                                    $validated['password'],
                            ]);


                    /*
                    |--------------------------------------------------------------------------
                    | ACTIVITY LOG
                    |--------------------------------------------------------------------------
                    |
                    | Tuyệt đối không ghi password
                    | hoặc password_confirmation.
                    |
                    */

                    ActivityLogger::log(
                        action:
                            'USER_CREATED',

                        description:
                            'ADMIN đã tạo tài khoản nhân sự '
                            .$user->name
                            .' với vai trò '
                            .$role->code
                            .'.',

                        entity:
                            $user,

                        oldValues:
                            null,

                        newValues: [
                            'user_id' =>
                                $user->id,

                            'name' =>
                                $user->name,

                            'email' =>
                                $user->email,

                            'role_code' =>
                                $role->code,
                        ]
                    );


                    return $user;
                }
            );


        return redirect()
            ->route(
                'admin.users.show',
                $user->id
            )
            ->with(
                'success',
                'Đã tạo tài khoản nhân sự thành công.'
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
     * ADMIN thay đổi vai trò nhân sự.
     *
     * Chỉ cho phép:
     *
     * STAFF <-> TECHNICIAN
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
                | CURRENT ROLE MUST BE WORKFORCE
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
                        ->whereIn(
                            'code',
                            self::WORKFORCE_ROLES
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
                | UPDATE
                |--------------------------------------------------------------------------
                */

                $oldValues = [
                    'role_id' =>
                        $lockedUser->role_id,

                    'role_code' =>
                        $currentRole,
                ];


                $lockedUser->update([
                    'role_id' =>
                        $newRole->id,
                ]);


                /*
                |--------------------------------------------------------------------------
                | ACTIVITY LOG
                |--------------------------------------------------------------------------
                */

                ActivityLogger::log(
                    action:
                        'USER_ROLE_CHANGED',

                    description:
                        'ADMIN đã chuyển vai trò của '
                        .$lockedUser->name
                        .' từ '
                        .$currentRole
                        .' sang '
                        .$newRoleCode
                        .'.',

                    entity:
                        $lockedUser,

                    oldValues:
                        $oldValues,

                    newValues: [
                        'role_id' =>
                            $newRole->id,

                        'role_code' =>
                            $newRoleCode,
                    ]
                );
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