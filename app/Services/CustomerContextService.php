<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CustomerContextService
{
    /**
     * Phân tích câu hỏi liên quan tới
     * dữ liệu CUSTOMER và trả lời
     * trực tiếp từ database.
     *
     * Trả về null nếu câu hỏi không thuộc
     * nhóm dữ liệu cá nhân.
     */
    public function answer(
        User $user,
        string $message
    ): ?array {
        $intent =
            $this->detectIntent(
                $message
            );

        if (!$intent) {
            return null;
        }

        $user->loadMissing([
            'role',
            'customer',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Chỉ CUSTOMER được truy vấn
        | dữ liệu cá nhân khách hàng
        |--------------------------------------------------------------------------
        */

        if (
            !$user->role
            ||
            $user->role->code !== 'CUSTOMER'
        ) {
            return [
                'content' => implode(
                    "\n\n",
                    [
                        'Câu hỏi này liên quan đến dữ liệu cá nhân của khách hàng.',
                        'Chức năng tra cứu xe, lịch hẹn, lịch sử bảo dưỡng và hóa đơn cá nhân hiện chỉ áp dụng cho tài khoản CUSTOMER.',
                    ]
                ),

                'sources' => [],

                'mode' => 'customer_data',
            ];
        }

        if (!$user->customer) {
            return [
                'content' =>
                    'Không tìm thấy hồ sơ khách hàng gắn với tài khoản hiện tại.',

                'sources' => [],

                'mode' => 'customer_data',
            ];
        }

        $customer =
            $user->customer;

        return match ($intent) {
            'RECOMMENDATION' =>
                $this->answerRecommendations(
                    $customer,
                    $message
                ),

            'APPOINTMENT' =>
                $this->answerAppointments(
                    $customer,
                    $message
                ),

            'INVOICE' =>
                $this->answerInvoices(
                    $customer,
                    $message
                ),

            'MAINTENANCE_HISTORY' =>
                $this->answerMaintenanceHistory(
                    $customer,
                    $message
                ),

            'VEHICLE' =>
                $this->answerVehicles(
                    $customer,
                    $message
                ),

            default => null,
        };
    }


    /*
    |--------------------------------------------------------------------------
    | PUBLIC DATA METHODS
    |--------------------------------------------------------------------------
    */

    public function getVehicles(
        Customer $customer
    ): Collection {
        return $customer
            ->vehicles()
            ->with([
                'brand',
                'vehicleModel',
            ])
            ->orderByDesc('created_at')
            ->get();
    }


    public function getUpcomingAppointments(
        Customer $customer,
        int $limit = 5
    ): Collection {
        return Appointment::with([
            'vehicle.brand',
            'vehicle.vehicleModel',
            'services',
        ])
            ->where(
                'customer_id',
                $customer->id
            )
            ->whereIn(
                'status',
                [
                    'PENDING',
                    'CONFIRMED',
                ]
            )
            ->whereDate(
                'appointment_date',
                '>=',
                now()->toDateString()
            )
            ->orderBy(
                'appointment_date'
            )
            ->orderBy(
                'appointment_time'
            )
            ->limit($limit)
            ->get();
    }


    public function getRecentMaintenance(
        Customer $customer,
        int $limit = 5
    ): Collection {
        return ServiceOrder::with([
            'vehicle.brand',
            'vehicle.vehicleModel',
            'technician',
            'items',
        ])
            ->where(
                'customer_id',
                $customer->id
            )
            ->where(
                'status',
                'COMPLETED'
            )
            ->orderByDesc(
                'completed_at'
            )
            ->limit($limit)
            ->get();
    }


    public function getRecentInvoices(
        Customer $customer,
        int $limit = 5
    ): Collection {
        return Invoice::with([
            'serviceOrder.vehicle.brand',
            'serviceOrder.vehicle.vehicleModel',
        ])
            ->where(
                'customer_id',
                $customer->id
            )
            ->orderByDesc(
                'issued_at'
            )
            ->limit($limit)
            ->get();
    }


    public function getUnpaidInvoiceSummary(
        Customer $customer,
        int $limit = 5
    ): array {
        $query =
            Invoice::with([
                'serviceOrder.vehicle.brand',
                'serviceOrder.vehicle.vehicleModel',
            ])
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->where(
                    'payment_status',
                    Invoice::STATUS_UNPAID
                );

        $count =
            (clone $query)->count();

        $amount =
            (float)
            (clone $query)->sum(
                'total_amount'
            );

        $invoices =
            $query
                ->orderByDesc(
                    'issued_at'
                )
                ->limit($limit)
                ->get();

        return [
            'count' => $count,
            'amount' => $amount,
            'invoices' => $invoices,
        ];
    }


    /**
     * Recommendation dựa trên
     * lịch sử bảo dưỡng thực tế.
     */
    public function getMaintenanceRecommendations(
        Customer $customer,
        ?Collection $vehicles = null,
        int $limit = 6
    ): Collection {
        $vehicles =
            $vehicles
            ?? $this->getVehicles(
                $customer
            );

        if ($vehicles->isEmpty()) {
            return collect();
        }

        $intervalServices =
            Service::where(
                'is_active',
                true
            )
                ->where(
                    function ($query) {
                        $query
                            ->whereNotNull(
                                'mileage_interval'
                            )
                            ->orWhereNotNull(
                                'month_interval'
                            );
                    }
                )
                ->orderBy('name')
                ->get();

        $completedOrders =
            ServiceOrder::with([
                'items',
            ])
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->where(
                    'status',
                    'COMPLETED'
                )
                ->whereIn(
                    'vehicle_id',
                    $vehicles->pluck('id')
                )
                ->orderByDesc(
                    'completed_at'
                )
                ->get()
                ->groupBy(
                    'vehicle_id'
                );

        return $this
            ->buildMaintenanceRecommendations(
                $vehicles,
                $intervalServices,
                $completedOrders
            )
            ->take($limit)
            ->values();
    }


    /*
    |--------------------------------------------------------------------------
    | VEHICLE ANSWER
    |--------------------------------------------------------------------------
    */

    private function answerVehicles(
        Customer $customer,
        string $message
    ): array {
        $allVehicles =
            $this->getVehicles(
                $customer
            );

        if ($allVehicles->isEmpty()) {
            return [
                'content' =>
                    'Tài khoản của bạn hiện chưa có phương tiện nào được đăng ký trên AutoCare.',

                'sources' => [],

                'mode' => 'customer_data',
            ];
        }

        $selection =
            $this->selectVehicles(
                $customer,
                $message,
                $allVehicles
            );

        if (
            $selection['specific']
            &&
            $selection['vehicles']->isEmpty()
        ) {
            return $this
                ->vehicleNotFoundResponse(
                    $allVehicles,
                    $selection[
                        'requested_label'
                    ]
                );
        }

        $vehicles =
            $selection['vehicles'];

        $lines = [
            $vehicles->count() === 1
                ? 'Mình tìm thấy phương tiện sau trong tài khoản của bạn:'
                : 'Các phương tiện hiện có trong tài khoản của bạn:',
        ];

        $sources = [];

        foreach ($vehicles as $vehicle) {
            $vehicleName =
                $this->vehicleName(
                    $vehicle
                );

            $details = [
                "• {$vehicleName}",
                "biển số {$vehicle->license_plate}",
                'ODO '
                    . number_format(
                        (int)
                        $vehicle->current_mileage,
                        0,
                        ',',
                        '.'
                    )
                    . ' km',
            ];

            if ($vehicle->manufacture_year) {
                $details[] =
                    'năm '
                    . $vehicle
                        ->manufacture_year;
            }

            if ($vehicle->fuel_type) {
                $details[] =
                    'nhiên liệu '
                    . $vehicle
                        ->fuel_type;
            }

            $lines[] =
                implode(
                    ' · ',
                    $details
                );

            $sources[] = [
                'type' => 'VEHICLE',
                'id' => $vehicle->id,
                'title' =>
                    $vehicleName
                    . ' - '
                    . $vehicle->license_plate,
            ];
        }

        return [
            'content' =>
                implode(
                    "\n",
                    $lines
                ),

            'sources' =>
                $sources,

            'mode' =>
                'customer_data',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | APPOINTMENT ANSWER
    |--------------------------------------------------------------------------
    */

    private function answerAppointments(
        Customer $customer,
        string $message
    ): array {
        $allVehicles =
            $this->getVehicles(
                $customer
            );

        $selection =
            $this->selectVehicles(
                $customer,
                $message,
                $allVehicles
            );

        if (
            $selection['specific']
            &&
            $selection['vehicles']->isEmpty()
        ) {
            return $this
                ->vehicleNotFoundResponse(
                    $allVehicles,
                    $selection[
                        'requested_label'
                    ]
                );
        }

        $query =
            Appointment::with([
                'vehicle.brand',
                'vehicle.vehicleModel',
                'services',
            ])
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->whereIn(
                    'status',
                    [
                        'PENDING',
                        'CONFIRMED',
                    ]
                )
                ->whereDate(
                    'appointment_date',
                    '>=',
                    now()->toDateString()
                );

        if ($selection['specific']) {
            $query->whereIn(
                'vehicle_id',
                $selection[
                    'vehicles'
                ]->pluck('id')
            );
        }

        $appointments =
            $query
                ->orderBy(
                    'appointment_date'
                )
                ->orderBy(
                    'appointment_time'
                )
                ->limit(5)
                ->get();

        if ($appointments->isEmpty()) {
            if ($selection['specific']) {
                $vehicleNames =
                    $selection[
                        'vehicles'
                    ]
                        ->map(
                            fn ($vehicle) =>
                                $this
                                    ->vehicleName(
                                        $vehicle
                                    )
                                . ' - '
                                . $vehicle
                                    ->license_plate
                        )
                        ->implode(', ');

                return [
                    'content' =>
                        "Hiện không có lịch hẹn PENDING hoặc CONFIRMED sắp tới cho {$vehicleNames}.",

                    'sources' => [],

                    'mode' =>
                        'customer_data',
                ];
            }

            return [
                'content' => implode(
                    "\n\n",
                    [
                        'Hiện bạn không có lịch hẹn PENDING hoặc CONFIRMED nào từ hôm nay trở đi.',
                        'Nếu cần bảo dưỡng xe, bạn có thể tạo lịch mới tại mục Đặt lịch.',
                    ]
                ),

                'sources' => [],

                'mode' =>
                    'customer_data',
            ];
        }

        $lines = [
            'Các lịch hẹn sắp tới của bạn:',
        ];

        $sources = [];

        foreach ($appointments as $appointment) {
            $vehicleName =
                $this->vehicleName(
                    $appointment->vehicle
                );

            $statusText =
                match (
                    $appointment->status
                ) {
                    'PENDING' =>
                        'Chờ xác nhận',

                    'CONFIRMED' =>
                        'Đã xác nhận',

                    default =>
                        $appointment->status,
                };

            $serviceNames =
                $appointment
                    ->services
                    ->pluck('name')
                    ->filter()
                    ->implode(', ');

            $line =
                '• '
                . $appointment
                    ->appointment_code
                . ' · '
                . $vehicleName
                . ' - '
                . $appointment
                    ->vehicle
                    ->license_plate
                . ' · '
                . $appointment
                    ->appointment_date
                    ->format('d/m/Y')
                . ' '
                . substr(
                    $appointment
                        ->appointment_time,
                    0,
                    5
                )
                . ' · '
                . $statusText;

            if ($serviceNames !== '') {
                $line .=
                    ' · '
                    . $serviceNames;
            }

            $lines[] =
                $line;

            $sources[] = [
                'type' =>
                    'APPOINTMENT',

                'id' =>
                    $appointment->id,

                'title' =>
                    $appointment
                        ->appointment_code,
            ];
        }

        return [
            'content' =>
                implode(
                    "\n",
                    $lines
                ),

            'sources' =>
                $sources,

            'mode' =>
                'customer_data',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MAINTENANCE HISTORY ANSWER
    |--------------------------------------------------------------------------
    */

    private function answerMaintenanceHistory(
        Customer $customer,
        string $message
    ): array {
        $allVehicles =
            $this->getVehicles(
                $customer
            );

        $selection =
            $this->selectVehicles(
                $customer,
                $message,
                $allVehicles
            );

        if (
            $selection['specific']
            &&
            $selection['vehicles']->isEmpty()
        ) {
            return $this
                ->vehicleNotFoundResponse(
                    $allVehicles,
                    $selection[
                        'requested_label'
                    ]
                );
        }

        $query =
            ServiceOrder::with([
                'vehicle.brand',
                'vehicle.vehicleModel',
                'technician',
                'items',
            ])
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->where(
                    'status',
                    'COMPLETED'
                );

        if ($selection['specific']) {
            $query->whereIn(
                'vehicle_id',
                $selection[
                    'vehicles'
                ]->pluck('id')
            );
        }

        $orders =
            $query
                ->orderByDesc(
                    'completed_at'
                )
                ->limit(5)
                ->get();

        if ($orders->isEmpty()) {
            if ($selection['specific']) {
                $vehicleNames =
                    $selection[
                        'vehicles'
                    ]
                        ->map(
                            fn ($vehicle) =>
                                $this
                                    ->vehicleName(
                                        $vehicle
                                    )
                                . ' - '
                                . $vehicle
                                    ->license_plate
                        )
                        ->implode(', ');

                return [
                    'content' =>
                        "AutoCare chưa ghi nhận phiếu bảo dưỡng COMPLETED nào cho {$vehicleNames}.",

                    'sources' => [],

                    'mode' =>
                        'customer_data',
                ];
            }

            return [
                'content' =>
                    'AutoCare hiện chưa ghi nhận phiếu bảo dưỡng COMPLETED nào trong lịch sử của bạn.',

                'sources' => [],

                'mode' =>
                    'customer_data',
            ];
        }

        $lines = [
            'Các lần bảo dưỡng hoàn thành gần nhất:',
        ];

        $sources = [];

        foreach ($orders as $order) {
            $vehicleName =
                $this->vehicleName(
                    $order->vehicle
                );

            $services =
                $order
                    ->items
                    ->pluck(
                        'service_name'
                    )
                    ->filter()
                    ->implode(', ');

            $completedAt =
                $order->completed_at
                    ? $order
                        ->completed_at
                        ->format(
                            'd/m/Y H:i'
                        )
                    : 'Chưa có thời gian';

            $line =
                '• '
                . $order->order_code
                . ' · '
                . $vehicleName
                . ' - '
                . $order
                    ->vehicle
                    ->license_plate
                . ' · '
                . $completedAt
                . ' · ODO '
                . number_format(
                    (int)
                    $order->received_mileage,
                    0,
                    ',',
                    '.'
                )
                . ' km';

            if ($services !== '') {
                $line .=
                    ' · '
                    . $services;
            }

            $line .=
                ' · Tổng '
                . number_format(
                    (float)
                    $order->total_amount,
                    0,
                    ',',
                    '.'
                )
                . ' đ';

            $lines[] =
                $line;

            $sources[] = [
                'type' =>
                    'SERVICE_ORDER',

                'id' =>
                    $order->id,

                'title' =>
                    $order->order_code,
            ];
        }

        return [
            'content' =>
                implode(
                    "\n",
                    $lines
                ),

            'sources' =>
                $sources,

            'mode' =>
                'customer_data',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | INVOICE ANSWER
    |--------------------------------------------------------------------------
    */

    private function answerInvoices(
        Customer $customer,
        string $message
    ): array {
        $normalized =
            $this->normalize(
                $message
            );

        $allVehicles =
            $this->getVehicles(
                $customer
            );

        $selection =
            $this->selectVehicles(
                $customer,
                $message,
                $allVehicles
            );

        if (
            $selection['specific']
            &&
            $selection['vehicles']->isEmpty()
        ) {
            return $this
                ->vehicleNotFoundResponse(
                    $allVehicles,
                    $selection[
                        'requested_label'
                    ]
                );
        }

        $asksUnpaid =
            $this->containsAny(
                $normalized,
                [
                    'chua thanh toan',
                    'chua tra',
                    'con hoa don',
                    'con no',
                    'phai thanh toan',
                    'can thanh toan',
                ]
            );

        $query =
            Invoice::with([
                'serviceOrder.vehicle.brand',
                'serviceOrder.vehicle.vehicleModel',
            ])
                ->where(
                    'customer_id',
                    $customer->id
                );

        if ($selection['specific']) {
            $vehicleIds =
                $selection[
                    'vehicles'
                ]->pluck('id');

            $query->whereHas(
                'serviceOrder',
                function (
                    $serviceOrderQuery
                ) use (
                    $vehicleIds
                ) {
                    $serviceOrderQuery
                        ->whereIn(
                            'vehicle_id',
                            $vehicleIds
                        );
                }
            );
        }

        if ($asksUnpaid) {
            $query->where(
                'payment_status',
                Invoice::STATUS_UNPAID
            );

            $count =
                (clone $query)->count();

            $amount =
                (float)
                (clone $query)->sum(
                    'total_amount'
                );

            if ($count === 0) {
                if ($selection['specific']) {
                    $vehicleNames =
                        $selection[
                            'vehicles'
                        ]
                            ->map(
                                fn ($vehicle) =>
                                    $this
                                        ->vehicleName(
                                            $vehicle
                                        )
                                    . ' - '
                                    . $vehicle
                                        ->license_plate
                            )
                            ->implode(', ');

                    return [
                        'content' =>
                            "Hiện không có hóa đơn chưa thanh toán cho {$vehicleNames}.",

                        'sources' => [],

                        'mode' =>
                            'customer_data',
                    ];
                }

                return [
                    'content' =>
                        'Bạn hiện không có hóa đơn nào chưa thanh toán.',

                    'sources' => [],

                    'mode' =>
                        'customer_data',
                ];
            }

            $invoices =
                $query
                    ->orderByDesc(
                        'issued_at'
                    )
                    ->limit(5)
                    ->get();

            $lines = [
                'Bạn hiện có '
                    . $count
                    . ' hóa đơn chưa thanh toán, tổng giá trị '
                    . number_format(
                        $amount,
                        0,
                        ',',
                        '.'
                    )
                    . ' đ.',
            ];

            $sources = [];

            foreach ($invoices as $invoice) {
                $vehicle =
                    $invoice
                        ->serviceOrder
                        ?->vehicle;

                $vehicleName =
                    $vehicle
                        ? $this
                            ->vehicleName(
                                $vehicle
                            )
                        : 'Không xác định xe';

                $lines[] =
                    '• '
                    . $invoice
                        ->invoice_code
                    . ' · '
                    . $vehicleName
                    . ' · '
                    . number_format(
                        (float)
                        $invoice
                            ->total_amount,
                        0,
                        ',',
                        '.'
                    )
                    . ' đ'
                    . (
                        $invoice->issued_at
                            ? ' · lập '
                                . $invoice
                                    ->issued_at
                                    ->format(
                                        'd/m/Y'
                                    )
                            : ''
                    );

                $sources[] = [
                    'type' =>
                        'INVOICE',

                    'id' =>
                        $invoice->id,

                    'title' =>
                        $invoice
                            ->invoice_code,
                ];
            }

            return [
                'content' =>
                    implode(
                        "\n",
                        $lines
                    ),

                'sources' =>
                    $sources,

                'mode' =>
                    'customer_data',
            ];
        }

        $invoices =
            $query
                ->orderByDesc(
                    'issued_at'
                )
                ->limit(5)
                ->get();

        if ($invoices->isEmpty()) {
            if ($selection['specific']) {
                $vehicleNames =
                    $selection[
                        'vehicles'
                    ]
                        ->map(
                            fn ($vehicle) =>
                                $this
                                    ->vehicleName(
                                        $vehicle
                                    )
                                . ' - '
                                . $vehicle
                                    ->license_plate
                        )
                        ->implode(', ');

                return [
                    'content' =>
                        "Hiện chưa có hóa đơn nào cho {$vehicleNames}.",

                    'sources' => [],

                    'mode' =>
                        'customer_data',
                ];
            }

            return [
                'content' =>
                    'Tài khoản của bạn hiện chưa có hóa đơn nào.',

                'sources' => [],

                'mode' =>
                    'customer_data',
            ];
        }

        $lines = [
            'Các hóa đơn gần nhất của bạn:',
        ];

        $sources = [];

        foreach ($invoices as $invoice) {
            $statusText =
                match (
                    $invoice
                        ->payment_status
                ) {
                    Invoice::STATUS_UNPAID =>
                        'Chưa thanh toán',

                    Invoice::STATUS_PAID =>
                        'Đã thanh toán',

                    Invoice::STATUS_CANCELLED =>
                        'Đã hủy',

                    default =>
                        $invoice
                            ->payment_status,
                };

            $vehicle =
                $invoice
                    ->serviceOrder
                    ?->vehicle;

            $vehicleName =
                $vehicle
                    ? $this
                        ->vehicleName(
                            $vehicle
                        )
                    : 'Không xác định xe';

            $lines[] =
                '• '
                . $invoice
                    ->invoice_code
                . ' · '
                . $vehicleName
                . ' · '
                . number_format(
                    (float)
                    $invoice
                        ->total_amount,
                    0,
                    ',',
                    '.'
                )
                . ' đ · '
                . $statusText;

            $sources[] = [
                'type' =>
                    'INVOICE',

                'id' =>
                    $invoice->id,

                'title' =>
                    $invoice
                        ->invoice_code,
            ];
        }

        return [
            'content' =>
                implode(
                    "\n",
                    $lines
                ),

            'sources' =>
                $sources,

            'mode' =>
                'customer_data',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | RECOMMENDATION ANSWER
    |--------------------------------------------------------------------------
    */

    private function answerRecommendations(
        Customer $customer,
        string $message
    ): array {
        $allVehicles =
            $this->getVehicles(
                $customer
            );

        if ($allVehicles->isEmpty()) {
            return [
                'content' =>
                    'Bạn chưa có phương tiện trong hệ thống nên AutoCare chưa thể đưa ra gợi ý bảo dưỡng.',

                'sources' => [],

                'mode' =>
                    'customer_data',
            ];
        }

        $selection =
            $this->selectVehicles(
                $customer,
                $message,
                $allVehicles
            );

        /*
         * User chỉ rõ xe nhưng không sở hữu.
         */
        if (
            $selection['specific']
            &&
            $selection['vehicles']->isEmpty()
        ) {
            return $this
                ->vehicleNotFoundResponse(
                    $allVehicles,
                    $selection[
                        'requested_label'
                    ]
                );
        }

        $vehicles =
            $selection['vehicles'];

        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA LỊCH SỬ BẢO DƯỠNG CỦA XE
        |--------------------------------------------------------------------------
        */

        $completedVehicleIds =
            ServiceOrder::query()
                ->where(
                    'customer_id',
                    $customer->id
                )
                ->where(
                    'status',
                    'COMPLETED'
                )
                ->whereIn(
                    'vehicle_id',
                    $vehicles->pluck('id')
                )
                ->distinct()
                ->pluck(
                    'vehicle_id'
                )
                ->map(
                    fn ($id) =>
                        (int) $id
                );

        $vehiclesWithoutHistory =
            $vehicles
                ->filter(
                    fn ($vehicle) =>
                        !$completedVehicleIds
                            ->contains(
                                (int)
                                $vehicle->id
                            )
                )
                ->values();

        /*
         * Nếu xe chưa có bất kỳ lịch sử
         * COMPLETED nào trong AutoCare,
         * chuyển sang INITIAL INSPECTION.
         *
         * Không khẳng định đã đến hạn.
         */
        if (
            $vehiclesWithoutHistory
                ->isNotEmpty()
        ) {
            return $this
                ->answerInitialInspection(
                    $vehiclesWithoutHistory
                );
        }

        /*
        |--------------------------------------------------------------------------
        | XE ĐÃ CÓ LỊCH SỬ
        |--------------------------------------------------------------------------
        */

        $recommendations =
            $this
                ->getMaintenanceRecommendations(
                    $customer,
                    $vehicles,
                    6
                );

        if ($recommendations->isEmpty()) {
            $vehicleNames =
                $vehicles
                    ->map(
                        fn ($vehicle) =>
                            $this
                                ->vehicleName(
                                    $vehicle
                                )
                            . ' - '
                            . $vehicle
                                ->license_plate
                    )
                    ->implode(', ');

            return [
                'content' => implode(
                    "\n\n",
                    [
                        "AutoCare đã có lịch sử bảo dưỡng của {$vehicleNames}, nhưng hiện chưa có đủ dữ liệu dịch vụ có chu kỳ để xác định kỳ tiếp theo.",
                        'Bạn vẫn có thể yêu cầu kiểm tra tổng quát nếu xe có dấu hiệu bất thường hoặc chuẩn bị cho một hành trình dài.',
                    ]
                ),

                'sources' =>
                    $vehicles
                        ->map(
                            fn ($vehicle) => [
                                'type' =>
                                    'VEHICLE',

                                'id' =>
                                    $vehicle->id,

                                'title' =>
                                    $vehicle
                                        ->license_plate,
                            ]
                        )
                        ->values()
                        ->all(),

                'mode' =>
                    'customer_data',
            ];
        }

        $lines = [
            'Gợi ý bảo dưỡng dựa trên lịch sử thực tế và ODO hiện tại:',
        ];

        $sources = [];

        foreach (
            $recommendations
            as $recommendation
        ) {
            $vehicle =
                $recommendation[
                    'vehicle'
                ];

            $service =
                $recommendation[
                    'service'
                ];

            $vehicleName =
                $this->vehicleName(
                    $vehicle
                );

            $status =
                $recommendation['is_due']
                    ? 'ĐÃ TỚI HẠN'
                    : 'Sắp tới';

            $details = [];

            if (
                $recommendation[
                    'next_mileage'
                ] !== null
            ) {
                $details[] =
                    'mốc tiếp theo '
                    . number_format(
                        $recommendation[
                            'next_mileage'
                        ],
                        0,
                        ',',
                        '.'
                    )
                    . ' km';

                if (
                    !$recommendation[
                        'is_due'
                    ]
                    &&
                    $recommendation[
                        'remaining_mileage'
                    ] !== null
                ) {
                    $details[] =
                        'còn khoảng '
                        . number_format(
                            $recommendation[
                                'remaining_mileage'
                            ],
                            0,
                            ',',
                            '.'
                        )
                        . ' km';
                }
            }

            if (
                $recommendation[
                    'next_date'
                ] !== null
            ) {
                $details[] =
                    'mốc thời gian '
                    . $recommendation[
                        'next_date'
                    ]
                        ->format(
                            'd/m/Y'
                        );
            }

            $line =
                '• '
                . $vehicleName
                . ' - '
                . $vehicle
                    ->license_plate
                . ': '
                . $service->name
                . ' · '
                . $status;

            if (!empty($details)) {
                $line .=
                    ' · '
                    . implode(
                        ' · ',
                        $details
                    );
            }

            $lines[] =
                $line;

            $sources[] = [
                'type' =>
                    'MAINTENANCE_RECOMMENDATION',

                'vehicle_id' =>
                    $vehicle->id,

                'service_id' =>
                    $service->id,

                'title' =>
                    $service->name,
            ];
        }

        $lines[] =
            'Các mốc trên là gợi ý từ dữ liệu AutoCare; tình trạng thực tế của xe vẫn nên được kiểm tra khi có dấu hiệu bất thường.';

        return [
            'content' =>
                implode(
                    "\n",
                    $lines
                ),

            'sources' =>
                $sources,

            'mode' =>
                'customer_data',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL INSPECTION
    |--------------------------------------------------------------------------
    |
    | Dùng khi AutoCare chưa ghi nhận
    | bất kỳ ServiceOrder COMPLETED nào
    | cho phương tiện.
    |
    | Không khẳng định hạng mục đã đến hạn.
    | Chỉ đưa ra checklist kiểm tra ban đầu.
    |
    */

    private function answerInitialInspection(
        Collection $vehicles
    ): array {
        $activeServices =
            Service::with([
                'category',
            ])
                ->where(
                    'is_active',
                    true
                )
                ->get();

        $lines = [];

        $sources = [];

        foreach ($vehicles as $vehicle) {
            $vehicleName =
                $this->vehicleName(
                    $vehicle
                );

            $lines[] =
                "AutoCare chưa ghi nhận lần bảo dưỡng hoàn thành nào cho {$vehicleName} - {$vehicle->license_plate}.";

            $lines[] =
                'Vì chưa có mốc bảo dưỡng trước đó trong hệ thống, mình chưa thể khẳng định hạng mục nào đã đến hạn. Thay vào đó, bạn nên thực hiện một lần kiểm tra ban đầu để xác lập tình trạng hiện tại của xe.';

            $lines[] =
                'ODO hiện tại: '
                . number_format(
                    (int)
                    $vehicle
                        ->current_mileage,
                    0,
                    ',',
                    '.'
                )
                . ' km.';

            $suggestedServices =
                $this
                    ->getInitialInspectionServices(
                        $vehicle,
                        $activeServices,
                        6
                    );

            if (
                $suggestedServices
                    ->isNotEmpty()
            ) {
                $lines[] =
                    'Các hạng mục nên ưu tiên kiểm tra:';

                foreach (
                    $suggestedServices
                    as $service
                ) {
                    $details = [];

                    if (
                        $service
                            ->mileage_interval
                    ) {
                        $details[] =
                            'chu kỳ tham khảo '
                            . number_format(
                                (int)
                                $service
                                    ->mileage_interval,
                                0,
                                ',',
                                '.'
                            )
                            . ' km';
                    }

                    if (
                        $service
                            ->month_interval
                    ) {
                        $details[] =
                            $service
                                ->month_interval
                            . ' tháng';
                    }

                    $line =
                        '• '
                        . $service->name;

                    if (!empty($details)) {
                        $line .=
                            ' · '
                            . implode(
                                ' / ',
                                $details
                            );
                    }

                    $lines[] =
                        $line;

                    $sources[] = [
                        'type' =>
                            'INITIAL_INSPECTION',

                        'vehicle_id' =>
                            $vehicle->id,

                        'service_id' =>
                            $service->id,

                        'title' =>
                            $service->name,
                    ];
                }
            } else {
                /*
                 * Fallback nếu catalog dịch vụ
                 * chưa có dữ liệu phù hợp.
                 */
                $lines[] =
                    'Các hạng mục nên ưu tiên kiểm tra:';

                $lines[] =
                    '• Dầu động cơ và các loại chất lỏng.';

                $lines[] =
                    '• Hệ thống phanh.';

                $lines[] =
                    '• Lốp và áp suất lốp.';

                $lines[] =
                    '• Ắc quy và hệ thống điện.';

                $lines[] =
                    '• Nước làm mát động cơ.';

                $lines[] =
                    '• Các bộ lọc và hệ thống điều hòa.';
            }

            $lines[] =
                'Đây là đề xuất KIỂM TRA ban đầu, không có nghĩa là các hạng mục trên bắt buộc phải thay hoặc sửa ngay. Kỹ thuật viên nên kiểm tra tình trạng thực tế trước khi quyết định thực hiện dịch vụ.';

            $sources[] = [
                'type' =>
                    'VEHICLE',

                'id' =>
                    $vehicle->id,

                'title' =>
                    $vehicleName
                    . ' - '
                    . $vehicle
                        ->license_plate,
            ];

            $lines[] = '';
        }

        return [
            'content' =>
                trim(
                    implode(
                        "\n",
                        $lines
                    )
                ),

            'sources' =>
                $sources,

            'mode' =>
                'customer_data_initial_inspection',
        ];
    }


    /**
     * Lấy các hạng mục kiểm tra ban đầu
     * từ chính danh mục Service của AutoCare.
     *
     * Không hard-code ID service.
     */
    private function getInitialInspectionServices(
        $vehicle,
        Collection $services,
        int $limit = 6
    ): Collection {
        $mileage =
            max(
                0,
                (int)
                $vehicle
                    ->current_mileage
            );

        return $services
            ->map(
                function (
                    Service $service
                ) use (
                    $mileage
                ) {
                    return [
                        'service' =>
                            $service,

                        'score' =>
                            $this
                                ->initialInspectionScore(
                                    $service,
                                    $mileage
                                ),
                    ];
                }
            )
            ->filter(
                function ($item) {
                    $service =
                        $item['service'];

                    return
                        $item['score'] > 0
                        ||
                        $service
                            ->mileage_interval
                        !== null
                        ||
                        $service
                            ->month_interval
                        !== null;
                }
            )
            ->sortByDesc(
                'score'
            )
            ->take($limit)
            ->pluck('service')
            ->values();
    }


    /**
     * Ưu tiên các hạng mục cơ bản
     * khi kiểm tra xe chưa có lịch sử.
     *
     * Điểm chỉ dùng để SẮP XẾP
     * checklist kiểm tra.
     *
     * Không dùng để kết luận
     * dịch vụ đã đến hạn.
     */
    private function initialInspectionScore(
        Service $service,
        int $currentMileage
    ): int {
        $text =
            $this->normalize(
                $service->name
                . ' '
                . (
                    $service
                        ->category
                        ?->name
                    ?? ''
                )
            );

        $score = 0;

        $priorityTerms = [
            'dau dong co' => 100,
            'nhot dong co' => 100,
            'loc dau' => 95,
            'phanh' => 90,
            'lop' => 85,
            'ac quy' => 80,
            'nuoc lam mat' => 75,
            'loc gio dong co' => 72,
            'loc gio' => 68,
            'dieu hoa' => 62,
            'bugi' => 58,
            'day curoa' => 54,
            'hop so' => 50,
            'he thong dien' => 46,
            'den' => 40,
        ];

        foreach (
            $priorityTerms
            as $term => $weight
        ) {
            if (
                Str::contains(
                    $text,
                    $term
                )
            ) {
                $score =
                    max(
                        $score,
                        $weight
                    );
            }
        }

        /*
         * Dịch vụ có chu kỳ định kỳ
         * được ưu tiên hơn dịch vụ
         * không có interval.
         */
        if (
            $service
                ->mileage_interval
        ) {
            $interval =
                (int)
                $service
                    ->mileage_interval;

            $score += 20;

            /*
             * ODO chỉ dùng để ưu tiên
             * kiểm tra, KHÔNG dùng để
             * kết luận xe đã đến hạn.
             */
            if (
                $interval > 0
                &&
                $currentMileage
                >= $interval
            ) {
                $score += 20;
            } elseif (
                $interval > 0
                &&
                $currentMileage
                >=
                (int)
                round(
                    $interval * 0.8
                )
            ) {
                $score += 10;
            }
        }

        if (
            $service
                ->month_interval
        ) {
            $score += 10;
        }

        return $score;
    }


    /*
    |--------------------------------------------------------------------------
    | MAINTENANCE RECOMMENDATION ENGINE
    |--------------------------------------------------------------------------
    */

    private function buildMaintenanceRecommendations(
        Collection $vehicles,
        Collection $services,
        Collection $completedOrders
    ): Collection {
        $recommendations =
            collect();

        foreach ($vehicles as $vehicle) {
            $vehicleOrders =
                $completedOrders->get(
                    $vehicle->id,
                    collect()
                );

            foreach ($services as $service) {
                $lastOrder =
                    $vehicleOrders->first(
                        function ($order) use (
                            $service
                        ) {
                            return $order
                                ->items
                                ->contains(
                                    function ($item) use (
                                        $service
                                    ) {
                                        return
                                            (int)
                                            $item->service_id
                                            ===
                                            (int)
                                            $service->id;
                                    }
                                );
                        }
                    );

                /*
                 * Không có lịch sử thực hiện
                 * service này thì không tính
                 * kỳ tiếp theo.
                 */
                if (!$lastOrder) {
                    continue;
                }

                $nextMileage = null;

                if (
                    $service
                        ->mileage_interval
                ) {
                    $nextMileage =
                        (int)
                        $lastOrder
                            ->received_mileage
                        +
                        (int)
                        $service
                            ->mileage_interval;
                }

                $nextDate = null;

                if (
                    $service
                        ->month_interval
                    &&
                    $lastOrder
                        ->completed_at
                ) {
                    $nextDate =
                        $lastOrder
                            ->completed_at
                            ->copy()
                            ->addMonths(
                                (int)
                                $service
                                    ->month_interval
                            );
                }

                $dueByMileage =
                    $nextMileage !== null
                    &&
                    (int)
                    $vehicle
                        ->current_mileage
                    >=
                    $nextMileage;

                $dueByDate =
                    $nextDate !== null
                    &&
                    now()
                        ->greaterThanOrEqualTo(
                            $nextDate
                        );

                $isDue =
                    $dueByMileage
                    ||
                    $dueByDate;

                $remainingMileage =
                    $nextMileage !== null
                        ? max(
                            0,
                            $nextMileage
                            -
                            (int)
                            $vehicle
                                ->current_mileage
                        )
                        : null;

                $priority =
                    999999999;

                if ($isDue) {
                    $priority = 0;
                } elseif (
                    $remainingMileage
                    !== null
                ) {
                    $priority =
                        $remainingMileage;
                }

                $recommendations->push([
                    'vehicle' =>
                        $vehicle,

                    'service' =>
                        $service,

                    'last_order' =>
                        $lastOrder,

                    'next_mileage' =>
                        $nextMileage,

                    'next_date' =>
                        $nextDate,

                    'remaining_mileage' =>
                        $remainingMileage,

                    'is_due' =>
                        $isDue,

                    'due_by_mileage' =>
                        $dueByMileage,

                    'due_by_date' =>
                        $dueByDate,

                    'priority' =>
                        $priority,
                ]);
            }
        }

        return $recommendations
            ->sortBy([
                [
                    'is_due',
                    'desc',
                ],
                [
                    'priority',
                    'asc',
                ],
            ])
            ->values();
    }


    /*
    |--------------------------------------------------------------------------
    | VEHICLE RESOLUTION
    |--------------------------------------------------------------------------
    */

    private function selectVehicles(
        Customer $customer,
        string $message,
        ?Collection $vehicles = null
    ): array {
        $vehicles =
            $vehicles
            ?? $this->getVehicles(
                $customer
            );

        if ($vehicles->isEmpty()) {
            return [
                'vehicles' =>
                    collect(),

                'specific' =>
                    false,

                'requested_label' =>
                    null,
            ];
        }

        $question =
            $this->normalize(
                $message
            );

        $scored =
            $vehicles->map(
                function (
                    $vehicle
                ) use (
                    $question
                ) {
                    $score = 0;

                    $brand =
                        $this->normalize(
                            $vehicle
                                ->brand
                                ?->name
                            ?? ''
                        );

                    $model =
                        $this->normalize(
                            $vehicle
                                ->vehicleModel
                                ?->name
                            ?? ''
                        );

                    $plate =
                        $this->normalize(
                            $vehicle
                                ->license_plate
                            ?? ''
                        );

                    if (
                        $plate !== ''
                        &&
                        Str::contains(
                            $question,
                            $plate
                        )
                    ) {
                        $score += 20;
                    }

                    if (
                        $model !== ''
                        &&
                        Str::contains(
                            $question,
                            $model
                        )
                    ) {
                        $score += 10;
                    }

                    if (
                        $brand !== ''
                        &&
                        Str::contains(
                            $question,
                            $brand
                        )
                    ) {
                        $score += 5;
                    }

                    return [
                        'vehicle' =>
                            $vehicle,

                        'score' =>
                            $score,
                    ];
                }
            );

        $maxScore =
            (int)
            $scored->max(
                'score'
            );

        if ($maxScore > 0) {
            return [
                'vehicles' =>
                    $scored
                        ->filter(
                            fn ($item) =>
                                $item['score']
                                ===
                                $maxScore
                        )
                        ->pluck(
                            'vehicle'
                        )
                        ->values(),

                'specific' =>
                    true,

                'requested_label' =>
                    null,
            ];
        }

        $requestedLabel =
            $this
                ->detectRequestedVehicleLabel(
                    $message
                );

        if ($requestedLabel !== null) {
            return [
                'vehicles' =>
                    collect(),

                'specific' =>
                    true,

                'requested_label' =>
                    $requestedLabel,
            ];
        }

        return [
            'vehicles' =>
                $vehicles,

            'specific' =>
                false,

            'requested_label' =>
                null,
        ];
    }


    private function detectRequestedVehicleLabel(
        string $message
    ): ?string {
        $question =
            $this->normalize(
                $message
            );

        $matchedBrand =
            VehicleBrand::query()
                ->pluck('name')
                ->filter()
                ->sortByDesc(
                    fn ($name) =>
                        mb_strlen(
                            $this->normalize(
                                $name
                            )
                        )
                )
                ->first(
                    function ($name) use (
                        $question
                    ) {
                        $normalizedName =
                            $this->normalize(
                                $name
                            );

                        return
                            $normalizedName !== ''
                            &&
                            Str::contains(
                                $question,
                                $normalizedName
                            );
                    }
                );

        $matchedModel =
            VehicleModel::query()
                ->pluck('name')
                ->filter()
                ->sortByDesc(
                    fn ($name) =>
                        mb_strlen(
                            $this->normalize(
                                $name
                            )
                        )
                )
                ->first(
                    function ($name) use (
                        $question
                    ) {
                        $normalizedName =
                            $this->normalize(
                                $name
                            );

                        return
                            $normalizedName !== ''
                            &&
                            Str::contains(
                                $question,
                                $normalizedName
                            );
                    }
                );

        if (
            $matchedBrand
            ||
            $matchedModel
        ) {
            return trim(
                implode(
                    ' ',
                    array_filter([
                        $matchedBrand,
                        $matchedModel,
                    ])
                )
            );
        }

        if (
            preg_match(
                '/\b\d{2}[A-Z]{1,2}[\s\-.]?\d{3}[\s\-.]?\d{2}\b/ui',
                $message,
                $plateMatch
            )
        ) {
            return
                $plateMatch[0];
        }

        if (
            preg_match(
                '/\bxe\s+(.+?)\s+cua\s+toi\b/u',
                $question,
                $matches
            )
        ) {
            $descriptor =
                trim(
                    $matches[1]
                    ?? ''
                );

            $genericDescriptors = [
                '',
                'nao',
                'nay',
                'do',
                'hien tai',
                'dang dung',
                'dang su dung',
                'oto',
                'o to',
                'trong tai khoan',
                'trong he thong',
            ];

            if (
                $descriptor !== ''
                &&
                !in_array(
                    $descriptor,
                    $genericDescriptors,
                    true
                )
            ) {
                return $descriptor;
            }
        }

        return null;
    }


    private function vehicleNotFoundResponse(
        Collection $allVehicles,
        ?string $requestedLabel
    ): array {
        $requestedText =
            $requestedLabel
                ? " {$requestedLabel}"
                : '';

        $lines = [
            "Mình không tìm thấy xe{$requestedText} trong tài khoản của bạn.",
        ];

        if ($allVehicles->isNotEmpty()) {
            $lines[] =
                'Phương tiện hiện có trong tài khoản:';

            foreach ($allVehicles as $vehicle) {
                $lines[] =
                    '• '
                    . $this
                        ->vehicleName(
                            $vehicle
                        )
                    . ' - '
                    . $vehicle
                        ->license_plate
                    . ' · ODO '
                    . number_format(
                        (int)
                        $vehicle
                            ->current_mileage,
                        0,
                        ',',
                        '.'
                    )
                    . ' km';
            }

            $lines[] =
                'Bạn hãy kiểm tra lại tên xe hoặc hỏi về một trong các phương tiện trên.';
        }

        return [
            'content' =>
                implode(
                    "\n",
                    $lines
                ),

            'sources' =>
                $allVehicles
                    ->map(
                        fn ($vehicle) => [
                            'type' =>
                                'VEHICLE',

                            'id' =>
                                $vehicle->id,

                            'title' =>
                                $this
                                    ->vehicleName(
                                        $vehicle
                                    )
                                . ' - '
                                . $vehicle
                                    ->license_plate,
                        ]
                    )
                    ->values()
                    ->all(),

            'mode' =>
                'customer_data',
        ];
    }


    private function vehicleName(
        $vehicle
    ): string {
        $name =
            trim(
                ($vehicle
                    ->brand
                    ?->name ?? '')
                . ' '
                . ($vehicle
                    ->vehicleModel
                    ?->name ?? '')
            );

        return $name !== ''
            ? $name
            : 'Phương tiện';
    }


    /*
    |--------------------------------------------------------------------------
    | INTENT DETECTION
    |--------------------------------------------------------------------------
    */

    private function detectIntent(
        string $message
    ): ?string {
        $message =
            $this->normalize(
                $message
            );

        if (
            $this->containsAny(
                $message,
                [
                    'sap can bao duong',
                    'can bao duong gi',
                    'nen bao duong gi',
                    'toi han bao duong',
                    'den han bao duong',
                    'goi y bao duong',
                    'bao duong tiep theo',
                    'ky bao duong tiep theo',
                    'sap toi bao duong gi',
                    'nen kiem tra gi',
                    'can kiem tra gi',
                ]
            )
        ) {
            return 'RECOMMENDATION';
        }

        if (
            $this->containsAny(
                $message,
                [
                    'hoa don cua toi',
                    'hoa don toi',
                    'hoa don nao',
                    'hoa don gan nhat',
                    'chua thanh toan',
                    'da thanh toan',
                    'con hoa don',
                    'con no',
                    'phai thanh toan',
                    'can thanh toan',
                ]
            )
            ||
            (
                Str::contains(
                    $message,
                    'hoa don'
                )
                &&
                $this
                    ->hasOwnershipReference(
                        $message
                    )
            )
        ) {
            return 'INVOICE';
        }

        if (
            $this->containsAny(
                $message,
                [
                    'lich hen cua toi',
                    'lich hen toi',
                    'lich hen nao',
                    'lich hen sap toi',
                    'lich sap toi',
                    'toi da dat lich',
                    'toi co lich hen',
                    'dat lich chua',
                ]
            )
            ||
            (
                Str::contains(
                    $message,
                    'lich hen'
                )
                &&
                $this
                    ->hasOwnershipReference(
                        $message
                    )
            )
        ) {
            return 'APPOINTMENT';
        }

        if (
            $this->containsAny(
                $message,
                [
                    'lich su bao duong',
                    'bao duong gan nhat',
                    'lan gan nhat toi bao duong',
                    'lan bao duong gan nhat',
                    'toi da bao duong',
                    'bao duong truoc day',
                    'da bao duong gi',
                ]
            )
        ) {
            return 'MAINTENANCE_HISTORY';
        }

        if (
            $this->containsAny(
                $message,
                [
                    'xe cua toi',
                    'xe toi',
                    'phuong tien cua toi',
                    'odo cua toi',
                    'odo xe toi',
                    'odo hien tai',
                    'so km xe toi',
                    'bien so xe toi',
                    'vin xe toi',
                ]
            )
            ||
            (
                $this
                    ->hasOwnershipReference(
                        $message
                    )
                &&
                $this->containsAny(
                    $message,
                    [
                        'odo',
                        'so km',
                        'kilomet',
                        'bien so',
                        'vin',
                        'xe',
                        'phuong tien',
                    ]
                )
            )
        ) {
            return 'VEHICLE';
        }

        return null;
    }


    private function hasOwnershipReference(
        string $message
    ): bool {
        return $this->containsAny(
            $message,
            [
                'cua toi',
                'xe toi',
                'toi co',
                'toi da',
                'tai khoan cua toi',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STRING HELPERS
    |--------------------------------------------------------------------------
    */

    private function normalize(
        string $text
    ): string {
        $text =
            Str::lower(
                trim($text)
            );

        $text =
            Str::ascii(
                $text
            );

        $text =
            preg_replace(
                '/[^a-z0-9]+/u',
                ' ',
                $text
            );

        return trim(
            preg_replace(
                '/\s+/u',
                ' ',
                $text ?? ''
            )
            ?? ''
        );
    }


    private function containsAny(
        string $text,
        array $phrases
    ): bool {
        foreach ($phrases as $phrase) {
            if (
                Str::contains(
                    $text,
                    $this->normalize(
                        $phrase
                    )
                )
            ) {
                return true;
            }
        }

        return false;
    }
}