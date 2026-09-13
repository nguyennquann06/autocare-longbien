<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\ServiceCategory;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    /**
     * Múi giờ nghiệp vụ của AutoCare Long Biên.
     */
    private const APPOINTMENT_TIMEZONE =
        'Asia/Ho_Chi_Minh';


    /**
     * Danh sách lịch hẹn của khách hàng.
     */
    public function index()
    {
        $user = Auth::user();


        if (
            !$user ||
            !$user->customer
        ) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }


        $appointments =
            Appointment::with([
                'vehicle.brand',
                'vehicle.vehicleModel',
                'services',
            ])
                ->where(
                    'customer_id',
                    $user->customer->id
                )
                ->orderByDesc(
                    'appointment_date'
                )
                ->orderByDesc(
                    'appointment_time'
                )
                ->get();


        return view(
            'appointments.index',
            compact('appointments')
        );
    }


    /**
     * Form đặt lịch.
     */
    public function create()
    {
        $user = Auth::user();


        if (
            !$user ||
            !$user->customer
        ) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }


        $vehicles =
            $user
                ->customer
                ->vehicles()
                ->with([
                    'brand',
                    'vehicleModel',
                ])
                ->latest()
                ->get();


        $categories =
            ServiceCategory::with([
                'services' =>
                    function ($query) {
                        $query
                            ->where(
                                'is_active',
                                true
                            )
                            ->orderBy(
                                'name'
                            );
                    },
            ])
                ->where(
                    'is_active',
                    true
                )
                ->orderBy(
                    'name'
                )
                ->get();


        return view(
            'appointments.create',
            compact(
                'user',
                'vehicles',
                'categories'
            )
        );
    }


    /**
     * Lưu lịch hẹn.
     */
    public function store(
        Request $request
    ) {
        $user = Auth::user();


        if (
            !$user ||
            !$user->customer
        ) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }


        $customer =
            $user->customer;


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE INPUT
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'contact_name' =>
                $this->normalizeName(
                    $request->input(
                        'contact_name'
                    )
                ),

            'contact_phone' =>
                $this->normalizeVietnamPhone(
                    $request->input(
                        'contact_phone'
                    )
                ),

            'contact_email' =>
                $this->normalizeEmail(
                    $request->input(
                        'contact_email'
                    )
                ),

            'customer_note' =>
                $this->normalizeNullableText(
                    $request->input(
                        'customer_note'
                    )
                ),
        ]);


        $today =
            CarbonImmutable::now(
                self::APPOINTMENT_TIMEZONE
            )->toDateString();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate(
                [
                    /*
                    |--------------------------------------------------------------------------
                    | VEHICLE
                    |--------------------------------------------------------------------------
                    */

                    'vehicle_id' => [
                        'bail',
                        'required',
                        'integer',

                        Rule::exists(
                            'vehicles',
                            'id'
                        )->where(
                            function (
                                $query
                            ) use (
                                $customer
                            ) {
                                $query->where(
                                    'customer_id',
                                    $customer->id
                                );
                            }
                        ),
                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | SERVICES
                    |--------------------------------------------------------------------------
                    */

                    'service_ids' => [
                        'bail',
                        'required',
                        'array',
                        'min:1',
                    ],

                    'service_ids.*' => [
                        'bail',
                        'required',
                        'integer',
                        'distinct',

                        Rule::exists(
                            'services',
                            'id'
                        )->where(
                            function (
                                $query
                            ) {
                                $query->where(
                                    'is_active',
                                    true
                                );
                            }
                        ),
                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | CONTACT NAME
                    |--------------------------------------------------------------------------
                    */

                    'contact_name' => [
                        'bail',
                        'required',
                        'string',
                        'min:2',
                        'max:100',
                        "regex:/^(?=.*\\pL)[\\pL\\pM .'-]+$/u",
                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | PHONE
                    |--------------------------------------------------------------------------
                    |
                    | Sau normalize:
                    |
                    | +84 912 345 678
                    | 84 912 345 678
                    | 0912.345.678
                    |
                    | đều trở thành:
                    |
                    | 0912345678
                    |
                    */

                    'contact_phone' => [
                        'bail',
                        'required',
                        'string',

                        'regex:/^0(?:3[2-9]|5[25689]|7[06-9]|8[1-9]|9[0-9])[0-9]{7}$/',
                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | EMAIL
                    |--------------------------------------------------------------------------
                    */

                    'contact_email' => [
                        'bail',
                        'nullable',
                        'string',
                        'email:rfc',
                        'max:254',
                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | APPOINTMENT DATE
                    |--------------------------------------------------------------------------
                    */

                    'appointment_date' => [
                        'bail',
                        'required',
                        'date_format:Y-m-d',
                        'after_or_equal:'
                        . $today,
                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | APPOINTMENT TIME
                    |--------------------------------------------------------------------------
                    */

                    'appointment_time' => [
                        'bail',
                        'required',
                        'date_format:H:i',
                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | NOTE
                    |--------------------------------------------------------------------------
                    */

                    'customer_note' => [
                        'bail',
                        'nullable',
                        'string',
                        'max:1000',
                    ],
                ],
                [
                    /*
                    |--------------------------------------------------------------------------
                    | VEHICLE
                    |--------------------------------------------------------------------------
                    */

                    'vehicle_id.required' =>
                        'Vui lòng chọn phương tiện cần bảo dưỡng.',

                    'vehicle_id.integer' =>
                        'Phương tiện không hợp lệ.',

                    'vehicle_id.exists' =>
                        'Phương tiện không tồn tại hoặc không thuộc tài khoản của bạn.',


                    /*
                    |--------------------------------------------------------------------------
                    | SERVICES
                    |--------------------------------------------------------------------------
                    */

                    'service_ids.required' =>
                        'Vui lòng chọn ít nhất một dịch vụ.',

                    'service_ids.array' =>
                        'Danh sách dịch vụ không hợp lệ.',

                    'service_ids.min' =>
                        'Vui lòng chọn ít nhất một dịch vụ.',

                    'service_ids.*.required' =>
                        'Danh sách dịch vụ không hợp lệ.',

                    'service_ids.*.integer' =>
                        'Có dịch vụ không hợp lệ.',

                    'service_ids.*.distinct' =>
                        'Một dịch vụ không được chọn nhiều lần.',

                    'service_ids.*.exists' =>
                        'Có dịch vụ không tồn tại hoặc đã ngừng hoạt động.',


                    /*
                    |--------------------------------------------------------------------------
                    | CONTACT NAME
                    |--------------------------------------------------------------------------
                    */

                    'contact_name.required' =>
                        'Vui lòng nhập tên người liên hệ.',

                    'contact_name.string' =>
                        'Tên người liên hệ không hợp lệ.',

                    'contact_name.min' =>
                        'Tên người liên hệ phải có ít nhất 2 ký tự.',

                    'contact_name.max' =>
                        'Tên người liên hệ không được vượt quá 100 ký tự.',

                    'contact_name.regex' =>
                        'Tên người liên hệ chỉ được chứa chữ cái, khoảng trắng và các ký tự tên hợp lệ.',


                    /*
                    |--------------------------------------------------------------------------
                    | PHONE
                    |--------------------------------------------------------------------------
                    */

                    'contact_phone.required' =>
                        'Vui lòng nhập số điện thoại liên hệ.',

                    'contact_phone.string' =>
                        'Số điện thoại không hợp lệ.',

                    'contact_phone.regex' =>
                        'Số điện thoại không đúng định dạng số di động Việt Nam. Ví dụ: 0912345678.',


                    /*
                    |--------------------------------------------------------------------------
                    | EMAIL
                    |--------------------------------------------------------------------------
                    */

                    'contact_email.string' =>
                        'Email liên hệ không hợp lệ.',

                    'contact_email.email' =>
                        'Email liên hệ không đúng định dạng. Ví dụ: example@email.com.',

                    'contact_email.max' =>
                        'Email liên hệ không được vượt quá 254 ký tự.',


                    /*
                    |--------------------------------------------------------------------------
                    | DATE
                    |--------------------------------------------------------------------------
                    */

                    'appointment_date.required' =>
                        'Vui lòng chọn ngày đặt lịch.',

                    'appointment_date.date_format' =>
                        'Ngày đặt lịch không hợp lệ.',

                    'appointment_date.after_or_equal' =>
                        'Ngày đặt lịch không được nằm trong quá khứ.',


                    /*
                    |--------------------------------------------------------------------------
                    | TIME
                    |--------------------------------------------------------------------------
                    */

                    'appointment_time.required' =>
                        'Vui lòng chọn giờ đặt lịch.',

                    'appointment_time.date_format' =>
                        'Giờ đặt lịch không hợp lệ.',


                    /*
                    |--------------------------------------------------------------------------
                    | NOTE
                    |--------------------------------------------------------------------------
                    */

                    'customer_note.string' =>
                        'Ghi chú không hợp lệ.',

                    'customer_note.max' =>
                        'Ghi chú không được vượt quá 1000 ký tự.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | EXACT DATE + TIME VALIDATION
        |--------------------------------------------------------------------------
        |
        | Không dùng strtotime()/time()
        | để tránh phụ thuộc timezone mặc định của PHP.
        |
        */

        $appointmentDateTime =
            CarbonImmutable::createFromFormat(
                'Y-m-d H:i',
                $validated[
                    'appointment_date'
                ]
                . ' '
                . $validated[
                    'appointment_time'
                ],
                self::APPOINTMENT_TIMEZONE
            );


        $currentDateTime =
            CarbonImmutable::now(
                self::APPOINTMENT_TIMEZONE
            );


        if (
            !$appointmentDateTime
            ||
            $appointmentDateTime
                ->lessThanOrEqualTo(
                    $currentDateTime
                )
        ) {
            return back()
                ->withErrors([
                    'appointment_time' =>
                        'Thời gian đặt lịch phải lớn hơn thời điểm hiện tại.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE VEHICLE APPOINTMENT
        |--------------------------------------------------------------------------
        |
        | Không cho cùng một xe có hai lịch
        | còn hiệu lực tại chính xác cùng thời điểm.
        |
        */

        $duplicateAppointment =
            Appointment::query()
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->where(
                    'vehicle_id',
                    $validated[
                        'vehicle_id'
                    ]
                )
                ->where(
                    'appointment_date',
                    $validated[
                        'appointment_date'
                    ]
                )
                ->where(
                    'appointment_time',
                    $validated[
                        'appointment_time'
                    ]
                )
                ->whereNotIn(
                    'status',
                    [
                        'CANCELLED',
                    ]
                )
                ->exists();


        if ($duplicateAppointment) {
            return back()
                ->withErrors([
                    'appointment_time' =>
                        'Phương tiện này đã có một lịch hẹn tại đúng thời gian bạn chọn. Vui lòng chọn thời gian khác.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | LOAD SERVICES FROM DATABASE
        |--------------------------------------------------------------------------
        */

        $services =
            Service::whereIn(
                'id',
                $validated[
                    'service_ids'
                ]
            )
                ->where(
                    'is_active',
                    true
                )
                ->get();


        if (
            $services->count()
            !==
            count(
                $validated[
                    'service_ids'
                ]
            )
        ) {
            return back()
                ->withErrors([
                    'service_ids' =>
                        'Có dịch vụ không hợp lệ hoặc đã ngừng hoạt động.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | SERVER-SIDE PRICE
        |--------------------------------------------------------------------------
        |
        | Không tin giá gửi từ frontend.
        |
        */

        $estimatedTotal =
            $services->sum(
                function (
                    $service
                ) {
                    return (float)
                        $service
                            ->base_price;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | SERVER-SIDE DURATION
        |--------------------------------------------------------------------------
        */

        $estimatedDuration =
            $services->sum(
                function (
                    $service
                ) {
                    return (int)
                        (
                            $service
                                ->estimated_duration_minutes
                            ?? 0
                        );
                }
            );


        /*
        |--------------------------------------------------------------------------
        | CREATE APPOINTMENT
        |--------------------------------------------------------------------------
        */

        $appointment =
            DB::transaction(
                function () use (
                    $validated,
                    $customer,
                    $services,
                    $estimatedTotal,
                    $estimatedDuration
                ) {
                    $appointment =
                        Appointment::create([
                            'appointment_code' =>
                                $this
                                    ->generateAppointmentCode(),

                            'customer_id' =>
                                $customer->id,

                            'vehicle_id' =>
                                $validated[
                                    'vehicle_id'
                                ],

                            'contact_name' =>
                                $validated[
                                    'contact_name'
                                ],

                            'contact_phone' =>
                                $validated[
                                    'contact_phone'
                                ],

                            'contact_email' =>
                                $validated[
                                    'contact_email'
                                ]
                                ?? null,

                            'appointment_date' =>
                                $validated[
                                    'appointment_date'
                                ],

                            'appointment_time' =>
                                $validated[
                                    'appointment_time'
                                ],

                            'estimated_total' =>
                                $estimatedTotal,

                            'estimated_duration_minutes' =>
                                $estimatedDuration,

                            'status' =>
                                'PENDING',

                            'customer_note' =>
                                $validated[
                                    'customer_note'
                                ]
                                ?? null,

                            'staff_note' =>
                                null,
                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | SNAPSHOT SERVICES
                    |--------------------------------------------------------------------------
                    */

                    $pivotData =
                        [];


                    foreach (
                        $services
                        as $service
                    ) {
                        $pivotData[
                            $service->id
                        ] = [
                            'price' =>
                                $service
                                    ->base_price,

                            'estimated_duration_minutes' =>
                                $service
                                    ->estimated_duration_minutes,
                        ];
                    }


                    $appointment
                        ->services()
                        ->attach(
                            $pivotData
                        );


                    return $appointment;
                }
            );


        return redirect()
            ->route(
                'appointments.index'
            )
            ->with(
                'success',
                'Đặt lịch thành công. Mã lịch hẹn của bạn là '
                . $appointment
                    ->appointment_code
                . '.'
            );
    }


    /**
     * Xem chi tiết lịch hẹn.
     */
    public function show(
        Appointment $appointment
    ) {
        $this->authorizeAppointmentOwner(
            $appointment
        );


        $appointment->load([
            'vehicle.brand',
            'vehicle.vehicleModel',
            'services',
        ]);


        return view(
            'appointments.show',
            compact('appointment')
        );
    }


    /**
     * Khách hàng tự hủy lịch.
     */
    public function cancel(
        Appointment $appointment
    ) {
        $this->authorizeAppointmentOwner(
            $appointment
        );


        if (
            $appointment->status
            !== 'PENDING'
        ) {
            return redirect()
                ->route(
                    'appointments.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    'Chỉ lịch hẹn đang chờ xác nhận mới có thể tự hủy.'
                );
        }


        $appointment->update([
            'status' =>
                'CANCELLED',
        ]);


        return redirect()
            ->route(
                'appointments.show',
                $appointment->id
            )
            ->with(
                'success',
                'Lịch hẹn đã được hủy thành công.'
            );
    }


    /**
     * Kiểm tra lịch hẹn có thuộc
     * khách hàng hiện tại không.
     */
    private function authorizeAppointmentOwner(
        Appointment $appointment
    ): void {
        $user = Auth::user();


        if (
            !$user ||
            !$user->customer
        ) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }


        if (
            (int)
            $appointment->customer_id
            !==
            (int)
            $user->customer->id
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập lịch hẹn này.'
            );
        }
    }


    /**
     * Sinh mã lịch hẹn.
     */
    private function generateAppointmentCode(): string
    {
        do {
            $code =
                'BK'
                . CarbonImmutable::now(
                    self::APPOINTMENT_TIMEZONE
                )->format(
                    'Ymd'
                )
                . strtoupper(
                    Str::random(
                        6
                    )
                );
        } while (
            Appointment::where(
                'appointment_code',
                $code
            )->exists()
        );


        return $code;
    }


    /**
     * Chuẩn hóa tên.
     */
    private function normalizeName(
        mixed $value
    ): ?string {
        if (
            $value === null
        ) {
            return null;
        }


        $value =
            preg_replace(
                '/\s+/u',
                ' ',
                trim(
                    (string)
                    $value
                )
            );


        return $value !== ''
            ? $value
            : null;
    }


    /**
     * Chuẩn hóa số điện thoại Việt Nam.
     *
     * Ví dụ:
     *
     * +84 912 345 678
     * 84912345678
     * 0912-345-678
     * 0912.345.678
     *
     * -> 0912345678
     */
    private function normalizeVietnamPhone(
        mixed $value
    ): ?string {
        if (
            $value === null
        ) {
            return null;
        }


        $value =
            trim(
                (string)
                $value
            );


        if ($value === '') {
            return null;
        }


        $compact =
            preg_replace(
                '/[\s.\-()]+/',
                '',
                $value
            );


        if (
            $compact === null
            ||
            $compact === ''
        ) {
            return null;
        }


        if (
            str_starts_with(
                $compact,
                '+84'
            )
        ) {
            $compact =
                '0'
                . substr(
                    $compact,
                    3
                );
        } elseif (
            preg_match(
                '/^84(?:3[2-9]|5[25689]|7[06-9]|8[1-9]|9[0-9])[0-9]{7}$/',
                $compact
            )
        ) {
            $compact =
                '0'
                . substr(
                    $compact,
                    2
                );
        }


        return $compact;
    }


    /**
     * Chuẩn hóa email.
     */
    private function normalizeEmail(
        mixed $value
    ): ?string {
        if (
            $value === null
        ) {
            return null;
        }


        $value =
            Str::lower(
                trim(
                    (string)
                    $value
                )
            );


        return $value !== ''
            ? $value
            : null;
    }


    /**
     * Chuẩn hóa ghi chú.
     */
    private function normalizeNullableText(
        mixed $value
    ): ?string {
        if (
            $value === null
        ) {
            return null;
        }


        $value =
            trim(
                (string)
                $value
            );


        return $value !== ''
            ? $value
            : null;
    }
}