<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\ServiceOrder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    /**
     * Dashboard khách hàng.
     */
    public function index()
    {
        $customer = $this->getAuthenticatedCustomer();

        /**
         * Danh sách xe của khách.
         */
        $vehicles = $customer
            ->vehicles()
            ->with([
                'brand',
                'vehicleModel',
            ])
            ->orderByDesc('created_at')
            ->get();


        /**
         * Lịch hẹn sắp tới.
         *
         * Chỉ lấy:
         * PENDING
         * CONFIRMED
         */
        $upcomingAppointments =
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
                )
                ->orderBy(
                    'appointment_date'
                )
                ->orderBy(
                    'appointment_time'
                )
                ->limit(5)
                ->get();


        /**
         * Các lần bảo dưỡng đã hoàn thành gần nhất.
         */
        $recentMaintenance =
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
                )
                ->orderByDesc(
                    'completed_at'
                )
                ->limit(5)
                ->get();


        /**
         * Hóa đơn gần nhất.
         */
        $recentInvoices =
            Invoice::with([
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
                ->limit(5)
                ->get();


        /**
         * Tổng số hóa đơn chưa thanh toán.
         */
        $unpaidInvoiceCount =
            Invoice::where(
                'customer_id',
                $customer->id
            )
                ->where(
                    'payment_status',
                    Invoice::STATUS_UNPAID
                )
                ->count();


        /**
         * Tổng số tiền chưa thanh toán.
         */
        $unpaidInvoiceAmount =
            (float) Invoice::where(
                'customer_id',
                $customer->id
            )
                ->where(
                    'payment_status',
                    Invoice::STATUS_UNPAID
                )
                ->sum(
                    'total_amount'
                );


        /**
         * Lấy các dịch vụ có chu kỳ
         * bảo dưỡng theo km hoặc tháng.
         */
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


        /**
         * Toàn bộ lịch sử hoàn thành.
         *
         * Load một lần để tránh truy vấn DB
         * liên tục khi tính gợi ý.
         */
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
                ->orderByDesc(
                    'completed_at'
                )
                ->get()
                ->groupBy(
                    'vehicle_id'
                );


        /**
         * Gợi ý bảo dưỡng dựa trên
         * lần thực hiện gần nhất.
         */
        $maintenanceRecommendations =
            $this->buildMaintenanceRecommendations(
                $vehicles,
                $intervalServices,
                $completedOrders
            );


        return view(
            'customer.dashboard',
            compact(
                'customer',
                'vehicles',
                'upcomingAppointments',
                'recentMaintenance',
                'recentInvoices',
                'unpaidInvoiceCount',
                'unpaidInvoiceAmount',
                'maintenanceRecommendations'
            )
        );
    }


    /**
     * Tính các gợi ý bảo dưỡng tiếp theo.
     *
     * Chỉ tính khi dịch vụ đã từng
     * được thực hiện trên xe.
     */
    private function buildMaintenanceRecommendations(
        Collection $vehicles,
        Collection $services,
        Collection $completedOrders
    ): Collection {
        $recommendations = collect();


        foreach ($vehicles as $vehicle) {

            $vehicleOrders =
                $completedOrders->get(
                    $vehicle->id,
                    collect()
                );


            foreach ($services as $service) {

                /**
                 * Tìm lần gần nhất mà xe
                 * thực hiện dịch vụ này.
                 */
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


                /**
                 * Nếu xe chưa từng thực hiện
                 * dịch vụ thì chưa đủ dữ liệu
                 * để tính kỳ tiếp theo.
                 */
                if (!$lastOrder) {
                    continue;
                }


                $nextMileage = null;

                if (
                    $service->mileage_interval
                ) {
                    $nextMileage =
                        (int)
                        $lastOrder->received_mileage
                        +
                        (int)
                        $service->mileage_interval;
                }


                $nextDate = null;

                if (
                    $service->month_interval
                    &&
                    $lastOrder->completed_at
                ) {
                    $nextDate =
                        $lastOrder
                            ->completed_at
                            ->copy()
                            ->addMonths(
                                (int)
                                $service->month_interval
                            );
                }


                /**
                 * Kiểm tra đã tới hạn theo ODO.
                 */
                $dueByMileage =
                    $nextMileage !== null
                    &&
                    (int)
                    $vehicle->current_mileage
                    >=
                    $nextMileage;


                /**
                 * Kiểm tra đã tới hạn theo thời gian.
                 */
                $dueByDate =
                    $nextDate !== null
                    &&
                    now()->greaterThanOrEqualTo(
                        $nextDate
                    );


                $isDue =
                    $dueByMileage
                    ||
                    $dueByDate;


                /**
                 * Số km còn lại.
                 */
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


                /**
                 * Dùng để ưu tiên gợi ý:
                 * - đã đến hạn lên đầu
                 * - sau đó tới những mục gần tới hạn.
                 */
                $priority = 999999999;

                if ($isDue) {
                    $priority = 0;
                } elseif (
                    $remainingMileage !== null
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


        /**
         * Chỉ hiển thị tối đa 6 gợi ý.
         */
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
            ->take(6)
            ->values();
    }


    /**
     * Lấy CUSTOMER hiện tại.
     */
    private function getAuthenticatedCustomer()
    {
        $user = Auth::user();

        if (!$user) {
            abort(
                403,
                'Bạn chưa đăng nhập.'
            );
        }

        $user->load([
            'role',
            'customer',
        ]);


        if (
            !$user->role
            ||
            $user->role->code !==
            'CUSTOMER'
        ) {
            abort(
                403,
                'Chức năng này chỉ dành cho khách hàng.'
            );
        }


        if (!$user->customer) {
            abort(
                403,
                'Không tìm thấy hồ sơ khách hàng.'
            );
        }


        return $user->customer;
    }
}