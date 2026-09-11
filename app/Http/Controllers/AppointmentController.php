<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AppointmentController extends Controller
{
    /**
     * Danh sách lịch hẹn của khách hàng.
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }

        $appointments = Appointment::with([
            'vehicle.brand',
            'vehicle.vehicleModel',
            'services',
        ])
            ->where(
                'customer_id',
                $user->customer->id
            )
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
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

        if (!$user || !$user->customer) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }

        $vehicles = $user->customer
            ->vehicles()
            ->with([
                'brand',
                'vehicleModel',
            ])
            ->latest()
            ->get();

        $categories = ServiceCategory::with([
            'services' => function ($query) {
                $query
                    ->where('is_active', true)
                    ->orderBy('name');
            },
        ])
            ->where('is_active', true)
            ->orderBy('name')
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
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }

        $customer = $user->customer;

        $validated = $request->validate(
            [
                'vehicle_id' => [
                    'required',

                    Rule::exists(
                        'vehicles',
                        'id'
                    )->where(
                        function ($query) use ($customer) {
                            $query->where(
                                'customer_id',
                                $customer->id
                            );
                        }
                    ),
                ],

                'service_ids' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'service_ids.*' => [
                    'required',
                    'integer',
                    'distinct',

                    Rule::exists(
                        'services',
                        'id'
                    )->where(
                        function ($query) {
                            $query->where(
                                'is_active',
                                true
                            );
                        }
                    ),
                ],

                'contact_name' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'contact_phone' => [
                    'required',
                    'string',
                    'max:20',
                    'regex:/^[0-9+\s\-.]{8,20}$/',
                ],

                'contact_email' => [
                    'nullable',
                    'email',
                    'max:150',
                ],

                'appointment_date' => [
                    'required',
                    'date',
                    'after_or_equal:today',
                ],

                'appointment_time' => [
                    'required',
                    'date_format:H:i',
                ],

                'customer_note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'vehicle_id.required' =>
                    'Vui lòng chọn phương tiện.',

                'vehicle_id.exists' =>
                    'Phương tiện không hợp lệ hoặc không thuộc tài khoản của bạn.',

                'service_ids.required' =>
                    'Vui lòng chọn ít nhất một dịch vụ.',

                'service_ids.array' =>
                    'Danh sách dịch vụ không hợp lệ.',

                'service_ids.min' =>
                    'Vui lòng chọn ít nhất một dịch vụ.',

                'service_ids.*.exists' =>
                    'Có dịch vụ không hợp lệ hoặc đã ngừng hoạt động.',

                'service_ids.*.distinct' =>
                    'Dịch vụ không được chọn trùng.',

                'contact_name.required' =>
                    'Vui lòng nhập tên người liên hệ.',

                'contact_phone.required' =>
                    'Vui lòng nhập số điện thoại.',

                'contact_phone.regex' =>
                    'Số điện thoại không đúng định dạng.',

                'contact_email.email' =>
                    'Email không đúng định dạng.',

                'appointment_date.required' =>
                    'Vui lòng chọn ngày đặt lịch.',

                'appointment_date.date' =>
                    'Ngày đặt lịch không hợp lệ.',

                'appointment_date.after_or_equal' =>
                    'Ngày đặt lịch không được ở trong quá khứ.',

                'appointment_time.required' =>
                    'Vui lòng chọn giờ đặt lịch.',

                'appointment_time.date_format' =>
                    'Giờ đặt lịch không hợp lệ.',

                'customer_note.max' =>
                    'Ghi chú không được vượt quá 1000 ký tự.',
            ]
        );

        /**
         * Kiểm tra ngày + giờ không nằm
         * trong quá khứ.
         */
        $appointmentDateTime = strtotime(
            $validated['appointment_date']
            . ' '
            . $validated['appointment_time']
        );

        if (
            $appointmentDateTime === false ||
            $appointmentDateTime <= time()
        ) {
            return back()
                ->withErrors([
                    'appointment_time' =>
                        'Thời gian đặt lịch phải lớn hơn thời điểm hiện tại.',
                ])
                ->withInput();
        }

        /**
         * Lấy dịch vụ trực tiếp từ DB.
         */
        $services = Service::whereIn(
            'id',
            $validated['service_ids']
        )
            ->where('is_active', true)
            ->get();

        if (
            $services->count() !==
            count($validated['service_ids'])
        ) {
            return back()
                ->withErrors([
                    'service_ids' =>
                        'Có dịch vụ không hợp lệ hoặc đã ngừng hoạt động.',
                ])
                ->withInput();
        }

        /**
         * Tính giá.
         */
        $estimatedTotal = $services->sum(
            function ($service) {
                return (float) $service->base_price;
            }
        );

        /**
         * Tính thời gian.
         */
        $estimatedDuration = $services->sum(
            function ($service) {
                return (int) (
                    $service->estimated_duration_minutes
                    ?? 0
                );
            }
        );

        /**
         * Transaction.
         */
        $appointment = DB::transaction(
            function () use (
                $validated,
                $customer,
                $services,
                $estimatedTotal,
                $estimatedDuration
            ) {
                $appointment = Appointment::create([
                    'appointment_code' =>
                        $this->generateAppointmentCode(),

                    'customer_id' =>
                        $customer->id,

                    'vehicle_id' =>
                        $validated['vehicle_id'],

                    'contact_name' =>
                        trim($validated['contact_name']),

                    'contact_phone' =>
                        trim($validated['contact_phone']),

                    'contact_email' =>
                        !empty($validated['contact_email'])
                            ? trim($validated['contact_email'])
                            : null,

                    'appointment_date' =>
                        $validated['appointment_date'],

                    'appointment_time' =>
                        $validated['appointment_time'],

                    'estimated_total' =>
                        $estimatedTotal,

                    'estimated_duration_minutes' =>
                        $estimatedDuration,

                    'status' =>
                        'PENDING',

                    'customer_note' =>
                        $validated['customer_note']
                        ?? null,

                    'staff_note' =>
                        null,
                ]);

                /**
                 * Snapshot dịch vụ.
                 */
                $pivotData = [];

                foreach ($services as $service) {
                    $pivotData[$service->id] = [
                        'price' =>
                            $service->base_price,

                        'estimated_duration_minutes' =>
                            $service
                                ->estimated_duration_minutes,
                    ];
                }

                $appointment
                    ->services()
                    ->attach($pivotData);

                return $appointment;
            }
        );

        return redirect()
            ->route('appointments.index')
            ->with(
                'success',
                'Đặt lịch thành công. Mã lịch hẹn của bạn là '
                . $appointment->appointment_code
                . '.'
            );
    }


    /**
     * Xem chi tiết lịch hẹn.
     */
    public function show(
        Appointment $appointment
    ) {
        /**
         * Kiểm tra quyền sở hữu.
         */
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
        /**
         * Không cho hủy lịch của người khác.
         */
        $this->authorizeAppointmentOwner(
            $appointment
        );

        /**
         * Chỉ lịch PENDING mới được
         * khách hàng tự hủy.
         */
        if ($appointment->status !== 'PENDING') {
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

        /**
         * Cập nhật trạng thái.
         *
         * Không xóa record để giữ lịch sử.
         */
        $appointment->update([
            'status' => 'CANCELLED',
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
     * khách hàng hiện tại hay không.
     */
    private function authorizeAppointmentOwner(
        Appointment $appointment
    ): void {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }

        if (
            (int) $appointment->customer_id !==
            (int) $user->customer->id
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
                . now()->format('Ymd')
                . strtoupper(
                    Str::random(6)
                );
        } while (
            Appointment::where(
                'appointment_code',
                $code
            )->exists()
        );

        return $code;
    }
}