<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StaffAppointmentController extends Controller
{
    /**
     * Danh sách lịch hẹn dành cho nhân viên.
     */
    public function index()
    {
        $this->authorizeStaff();

        $appointments = Appointment::with([
            'customer',
            'vehicle.brand',
            'vehicle.vehicleModel',
            'services',
        ])
            ->orderByDesc('appointment_date')
            ->orderByDesc('appointment_time')
            ->get();

        return view(
            'staff.appointments.index',
            compact('appointments')
        );
    }


    /**
     * Chi tiết lịch hẹn.
     */
    public function show(
        Appointment $appointment
    ) {
        $this->authorizeStaff();

        $appointment->load([
            'customer',
            'vehicle.brand',
            'vehicle.vehicleModel',
            'services',
        ]);

        return view(
            'staff.appointments.show',
            compact('appointment')
        );
    }


    /**
     * Cập nhật trạng thái lịch hẹn.
     */
    public function updateStatus(
        Request $request,
        Appointment $appointment
    ) {
        $this->authorizeStaff();

        $validated = $request->validate(
            [
                'status' => [
                    'required',

                    Rule::in([
                        'CONFIRMED',
                        'IN_PROGRESS',
                        'COMPLETED',
                    ]),
                ],

                'staff_note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'status.required' =>
                    'Vui lòng chọn trạng thái.',

                'status.in' =>
                    'Trạng thái không hợp lệ.',

                'staff_note.max' =>
                    'Ghi chú không được vượt quá 1000 ký tự.',
            ]
        );

        /**
         * Quy định luồng trạng thái.
         *
         * Chỉ được chuyển sang trạng thái kế tiếp.
         */
        $allowedTransitions = [
            'PENDING' => 'CONFIRMED',
            'CONFIRMED' => 'IN_PROGRESS',
            'IN_PROGRESS' => 'COMPLETED',
        ];

        /**
         * COMPLETED hoặc CANCELLED
         * không được chuyển tiếp.
         */
        if (
            !isset(
                $allowedTransitions[
                    $appointment->status
                ]
            )
        ) {
            return redirect()
                ->route(
                    'staff.appointments.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    'Lịch hẹn này không thể chuyển sang trạng thái khác.'
                );
        }

        /**
         * Không cho bỏ qua trạng thái.
         *
         * Ví dụ:
         * PENDING không thể nhảy thẳng
         * sang COMPLETED.
         */
        $expectedStatus =
            $allowedTransitions[
                $appointment->status
            ];

        if (
            $validated['status'] !==
            $expectedStatus
        ) {
            return redirect()
                ->route(
                    'staff.appointments.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    'Không thể chuyển trạng thái theo yêu cầu.'
                );
        }

        $appointment->update([
            'status' =>
                $validated['status'],

            'staff_note' =>
                !empty(
                    $validated['staff_note']
                )
                    ? trim(
                        $validated['staff_note']
                    )
                    : $appointment->staff_note,
        ]);

        return redirect()
            ->route(
                'staff.appointments.show',
                $appointment->id
            )
            ->with(
                'success',
                'Cập nhật trạng thái lịch hẹn thành công.'
            );
    }


    /**
     * Kiểm tra quyền khu vực nhân viên.
     *
     * Cho phép:
     * - STAFF
     * - ADMIN
     */
    private function authorizeStaff(): void
    {
        $user = Auth::user();

        if (!$user) {
            abort(
                403,
                'Bạn chưa đăng nhập.'
            );
        }

        if (!$user->role) {
            abort(
                403,
                'Tài khoản chưa được phân quyền.'
            );
        }

        if (
            !in_array(
                $user->role->code,
                [
                    'STAFF',
                    'ADMIN',
                ],
                true
            )
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập khu vực nhân viên.'
            );
        }
    }
}