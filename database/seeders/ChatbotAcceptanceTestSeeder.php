<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChatbotAcceptanceTestSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        $customerRole = Role::where(
            'code',
            'CUSTOMER'
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | VEHICLE BRANDS / MODELS
        |--------------------------------------------------------------------------
        */

        $mazdaBrand = VehicleBrand::where(
            'name',
            'Mazda'
        )->firstOrFail();


        $toyotaBrand = VehicleBrand::where(
            'name',
            'Toyota'
        )->firstOrFail();


        /*
         * Tạo riêng model "3" cho acceptance test
         * để tên xe hiển thị là:
         *
         * Mazda 3
         *
         * thay vì Mazda Mazda3.
         */
        $mazdaModel = VehicleModel::updateOrCreate(
            [
                'brand_id' => $mazdaBrand->id,
                'name' => '3',
            ],
            [
                'vehicle_type' => 'Sedan',
                'is_active' => true,
            ]
        );


        $toyotaModel = VehicleModel::where(
            'brand_id',
            $toyotaBrand->id
        )
            ->where(
                'name',
                'Corolla Cross'
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SERVICES USED BY ACCEPTANCE TEST
        |--------------------------------------------------------------------------
        */

        $maintenance5000 = Service::where(
            'code',
            'MAINTENANCE_5000'
        )->firstOrFail();


        /*
         * Service riêng phục vụ lịch hẹn TEST_A.
         */
        $maintenance50000 = Service::updateOrCreate(
            [
                'code' =>
                    'ACCEPTANCE_MAINTENANCE_50000',
            ],
            [
                'category_id' =>
                    $maintenance5000->category_id,

                'name' =>
                    'Bảo dưỡng định kỳ 50.000 km',

                'description' =>
                    'Dịch vụ dùng cho bộ acceptance test chatbot.',

                'base_price' =>
                    500000,

                'estimated_duration_minutes' =>
                    90,

                'mileage_interval' =>
                    50000,

                'month_interval' =>
                    12,

                'is_active' =>
                    true,
            ]
        );


        $maintenance10000 = Service::where(
            'code',
            'MAINTENANCE_10000'
        )->firstOrFail();


        $engineOil = Service::where(
            'code',
            'ENGINE_OIL_CHANGE'
        )->firstOrFail();


        $oilFilter = Service::where(
            'code',
            'OIL_FILTER_CHANGE'
        )->firstOrFail();


        $brakeInspection = Service::where(
            'code',
            'BRAKE_INSPECTION'
        )->firstOrFail();


        $tireRotation = Service::where(
            'code',
            'TIRE_ROTATION'
        )->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | TEST_A - USER
        |--------------------------------------------------------------------------
        */

        $userA = User::updateOrCreate(
            [
                'email' =>
                    'test.a@autocare.local',
            ],
            [
                'role_id' =>
                    $customerRole->id,

                'name' =>
                    'Khách hàng A',

                'email_verified_at' =>
                    now(),

                'password' =>
                    Hash::make(
                        'password'
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_A - CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customerA = Customer::updateOrCreate(
            [
                'user_id' =>
                    $userA->id,
            ],
            [
                'full_name' =>
                    'Khách hàng A',

                'phone' =>
                    '0900000001',

                'email' =>
                    'test.a@autocare.local',

                'note' =>
                    'Chatbot acceptance test account TEST_A',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_A - VEHICLE
        |--------------------------------------------------------------------------
        */

        $vehicleA = Vehicle::updateOrCreate(
            [
                'license_plate' =>
                    '30G-123.45',
            ],
            [
                'customer_id' =>
                    $customerA->id,

                'brand_id' =>
                    $mazdaBrand->id,

                'model_id' =>
                    $mazdaModel->id,

                'manufacture_year' =>
                    2020,

                'fuel_type' =>
                    'Xăng',

                'current_mileage' =>
                    48620,

                'note' =>
                    'TEST_A vehicle',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_A - APPOINTMENT
        |--------------------------------------------------------------------------
        */

        $appointmentA = Appointment::updateOrCreate(
            [
                'appointment_code' =>
                    'BK-A001',
            ],
            [
                'customer_id' =>
                    $customerA->id,

                'vehicle_id' =>
                    $vehicleA->id,

                'contact_name' =>
                    'Khách hàng A',

                'contact_phone' =>
                    '0900000001',

                'contact_email' =>
                    'test.a@autocare.local',

                'appointment_date' =>
                    '2026-10-10',

                'appointment_time' =>
                    '08:30:00',

                'estimated_total' =>
                    700000,

                'estimated_duration_minutes' =>
                    120,

                'status' =>
                    'CONFIRMED',

                'customer_note' =>
                    'Bảo dưỡng định kỳ 50.000 km + kiểm tra phanh',
            ]
        );


        /*
         * Appointment services của TEST_A.
         *
         * CustomerContextService đọc danh sách
         * dịch vụ từ relationship services().
         */
        $appointmentA
            ->services()
            ->sync([
                $maintenance50000->id => [
                    'price' =>
                        500000,

                    'estimated_duration_minutes' =>
                        90,
                ],

                $brakeInspection->id => [
                    'price' =>
                        200000,

                    'estimated_duration_minutes' =>
                        30,
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | TEST_A - SERVICE ORDER
        |--------------------------------------------------------------------------
        */

        $serviceOrderA = ServiceOrder::updateOrCreate(
            [
                'order_code' =>
                    'SO-A001',
            ],
            [
                /*
                 * Không gắn với BK-A001 vì BK-A001
                 * là lịch hẹn tương lai trong snapshot test.
                 */
                'appointment_id' =>
                    null,

                'customer_id' =>
                    $customerA->id,

                'vehicle_id' =>
                    $vehicleA->id,

                'created_by' =>
                    null,

                'technician_id' =>
                    null,

                'received_mileage' =>
                    47900,

                'status' =>
                    'COMPLETED',

                'received_at' =>
                    '2026-08-15 08:00:00',

                'started_at' =>
                    '2026-08-15 08:30:00',

                'completed_at' =>
                    '2026-08-15 10:30:00',

                'vehicle_condition' =>
                    'Bảo dưỡng định kỳ',

                'diagnosis' =>
                    'Thay dầu + lọc dầu, kiểm tra phanh',

                'staff_note' =>
                    'Acceptance test TEST_A',

                'service_total' =>
                    1250000,

                'parts_total' =>
                    0,

                'total_amount' =>
                    1250000,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_A - SERVICE ORDER ITEMS
        |--------------------------------------------------------------------------
        */

        ServiceOrderItem::updateOrCreate(
            [
                'service_order_id' =>
                    $serviceOrderA->id,

                'service_id' =>
                    $engineOil->id,
            ],
            [
                'service_name' =>
                    'Thay dầu động cơ',

                'unit_price' =>
                    700000,

                'quantity' =>
                    1,

                'line_total' =>
                    700000,

                'estimated_duration_minutes' =>
                    30,

                'status' =>
                    'COMPLETED',

                'technician_note' =>
                    'Acceptance test TEST_A',
            ]
        );


        ServiceOrderItem::updateOrCreate(
            [
                'service_order_id' =>
                    $serviceOrderA->id,

                'service_id' =>
                    $oilFilter->id,
            ],
            [
                'service_name' =>
                    'Thay lọc dầu động cơ',

                'unit_price' =>
                    300000,

                'quantity' =>
                    1,

                'line_total' =>
                    300000,

                'estimated_duration_minutes' =>
                    20,

                'status' =>
                    'COMPLETED',

                'technician_note' =>
                    'Acceptance test TEST_A',
            ]
        );


        ServiceOrderItem::updateOrCreate(
            [
                'service_order_id' =>
                    $serviceOrderA->id,

                'service_id' =>
                    $brakeInspection->id,
            ],
            [
                'service_name' =>
                    'Kiểm tra hệ thống phanh',

                'unit_price' =>
                    250000,

                'quantity' =>
                    1,

                'line_total' =>
                    250000,

                'estimated_duration_minutes' =>
                    30,

                'status' =>
                    'COMPLETED',

                'technician_note' =>
                    'Acceptance test TEST_A',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_A - INVOICE
        |--------------------------------------------------------------------------
        */

        Invoice::updateOrCreate(
            [
                'invoice_code' =>
                    'INV-A001',
            ],
            [
                'service_order_id' =>
                    $serviceOrderA->id,

                'customer_id' =>
                    $customerA->id,

                'created_by' =>
                    null,

                'service_total' =>
                    1250000,

                'parts_total' =>
                    0,

                'subtotal' =>
                    1250000,

                'discount_amount' =>
                    0,

                'tax_amount' =>
                    0,

                'total_amount' =>
                    1250000,

                'payment_status' =>
                    Invoice::STATUS_PAID,

                'payment_method' =>
                    Invoice::METHOD_BANK_TRANSFER,

                'issued_at' =>
                    '2026-08-15 10:35:00',

                'paid_at' =>
                    '2026-08-15 10:40:00',

                'note' =>
                    'Acceptance test TEST_A',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_B - USER
        |--------------------------------------------------------------------------
        */

        $userB = User::updateOrCreate(
            [
                'email' =>
                    'test.b@autocare.local',
            ],
            [
                'role_id' =>
                    $customerRole->id,

                'name' =>
                    'Khách hàng B',

                'email_verified_at' =>
                    now(),

                'password' =>
                    Hash::make(
                        'password'
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_B - CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customerB = Customer::updateOrCreate(
            [
                'user_id' =>
                    $userB->id,
            ],
            [
                'full_name' =>
                    'Khách hàng B',

                'phone' =>
                    '0900000002',

                'email' =>
                    'test.b@autocare.local',

                'note' =>
                    'Chatbot acceptance test account TEST_B',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_B - VEHICLE
        |--------------------------------------------------------------------------
        */

        $vehicleB = Vehicle::updateOrCreate(
            [
                'license_plate' =>
                    '30H-678.90',
            ],
            [
                'customer_id' =>
                    $customerB->id,

                'brand_id' =>
                    $toyotaBrand->id,

                'model_id' =>
                    $toyotaModel->id,

                'manufacture_year' =>
                    2022,

                'fuel_type' =>
                    'Xăng',

                'current_mileage' =>
                    36100,

                'note' =>
                    'TEST_B vehicle',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_B - APPOINTMENT
        |--------------------------------------------------------------------------
        */

        $appointmentB = Appointment::updateOrCreate(
            [
                'appointment_code' =>
                    'BK-B001',
            ],
            [
                'customer_id' =>
                    $customerB->id,

                'vehicle_id' =>
                    $vehicleB->id,

                'contact_name' =>
                    'Khách hàng B',

                'contact_phone' =>
                    '0900000002',

                'contact_email' =>
                    'test.b@autocare.local',

                'appointment_date' =>
                    '2026-10-12',

                'appointment_time' =>
                    '13:30:00',

                'estimated_total' =>
                    650000,

                'estimated_duration_minutes' =>
                    30,

                'status' =>
                    'PENDING',

                'customer_note' =>
                    'Thay dầu động cơ',
            ]
        );


        /*
         * Appointment services của TEST_B.
         */
        $appointmentB
            ->services()
            ->sync([
                $engineOil->id => [
                    'price' =>
                        650000,

                    'estimated_duration_minutes' =>
                        30,
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | TEST_B - SERVICE ORDER
        |--------------------------------------------------------------------------
        */

        $serviceOrderB = ServiceOrder::updateOrCreate(
            [
                'order_code' =>
                    'SO-B001',
            ],
            [
                'appointment_id' =>
                    null,

                'customer_id' =>
                    $customerB->id,

                'vehicle_id' =>
                    $vehicleB->id,

                'created_by' =>
                    null,

                'technician_id' =>
                    null,

                'received_mileage' =>
                    34800,

                'status' =>
                    'COMPLETED',

                'received_at' =>
                    '2026-07-20 13:00:00',

                'started_at' =>
                    '2026-07-20 13:30:00',

                'completed_at' =>
                    '2026-07-20 15:00:00',

                'vehicle_condition' =>
                    'Bảo dưỡng định kỳ',

                'diagnosis' =>
                    'Bảo dưỡng định kỳ, đảo lốp',

                'staff_note' =>
                    'Acceptance test TEST_B',

                'service_total' =>
                    980000,

                'parts_total' =>
                    0,

                'total_amount' =>
                    980000,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_B - SERVICE ORDER ITEMS
        |--------------------------------------------------------------------------
        */

        ServiceOrderItem::updateOrCreate(
            [
                'service_order_id' =>
                    $serviceOrderB->id,

                'service_id' =>
                    $maintenance10000->id,
            ],
            [
                /*
                 * Snapshot name cố định theo bộ
                 * acceptance test.
                 */
                'service_name' =>
                    'Bảo dưỡng định kỳ',

                'unit_price' =>
                    780000,

                'quantity' =>
                    1,

                'line_total' =>
                    780000,

                'estimated_duration_minutes' =>
                    90,

                'status' =>
                    'COMPLETED',

                'technician_note' =>
                    'Acceptance test TEST_B',
            ]
        );


        ServiceOrderItem::updateOrCreate(
            [
                'service_order_id' =>
                    $serviceOrderB->id,

                'service_id' =>
                    $tireRotation->id,
            ],
            [
                'service_name' =>
                    'Đảo lốp',

                'unit_price' =>
                    200000,

                'quantity' =>
                    1,

                'line_total' =>
                    200000,

                'estimated_duration_minutes' =>
                    30,

                'status' =>
                    'COMPLETED',

                'technician_note' =>
                    'Acceptance test TEST_B',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | TEST_B - INVOICE
        |--------------------------------------------------------------------------
        */

        Invoice::updateOrCreate(
            [
                'invoice_code' =>
                    'INV-B001',
            ],
            [
                'service_order_id' =>
                    $serviceOrderB->id,

                'customer_id' =>
                    $customerB->id,

                'created_by' =>
                    null,

                'service_total' =>
                    980000,

                'parts_total' =>
                    0,

                'subtotal' =>
                    980000,

                'discount_amount' =>
                    0,

                'tax_amount' =>
                    0,

                'total_amount' =>
                    980000,

                'payment_status' =>
                    Invoice::STATUS_PAID,

                'payment_method' =>
                    Invoice::METHOD_CASH,

                'issued_at' =>
                    '2026-07-20 15:05:00',

                'paid_at' =>
                    '2026-07-20 15:10:00',

                'note' =>
                    'Acceptance test TEST_B',
            ]
        );
    }
}