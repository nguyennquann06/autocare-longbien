<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StaffAppointmentController extends Controller
{
    /**
     * Danh sách lịch hẹn.
     */
    public function index(
        Request $request
    ) {
        $this->authorizeStaff();


        $allowedStatuses = [
            'ALL',
            'PENDING',
            'CONFIRMED',
            'IN_PROGRESS',
            'COMPLETED',
            'CANCELLED',
        ];


        $statusFilter =
            strtoupper(
                trim(
                    (string)
                    $request->query(
                        'status',
                        'ALL'
                    )
                )
            );


        if (
            !in_array(
                $statusFilter,
                $allowedStatuses,
                true
            )
        ) {
            $statusFilter =
                'ALL';
        }


        $statusCounts =
            Appointment::query()
                ->selectRaw(
                    'status, COUNT(*) as total'
                )
                ->groupBy(
                    'status'
                )
                ->pluck(
                    'total',
                    'status'
                );


        $totalAppointments =
            (int)
            $statusCounts->sum();


        $query =
            Appointment::with([
                'customer',
                'vehicle.brand',
                'vehicle.vehicleModel',
                'services',
                'serviceOrder.invoice',
            ]);


        if (
            $statusFilter !== 'ALL'
        ) {
            $query->where(
                'status',
                $statusFilter
            );
        }


        $appointments =
            $query
                ->orderByRaw("
                    CASE status
                        WHEN 'PENDING' THEN 1
                        WHEN 'CONFIRMED' THEN 2
                        WHEN 'IN_PROGRESS' THEN 3
                        WHEN 'COMPLETED' THEN 4
                        WHEN 'CANCELLED' THEN 5
                        ELSE 6
                    END
                ")
                ->orderByRaw("
                    CASE
                        WHEN status IN (
                            'PENDING',
                            'CONFIRMED',
                            'IN_PROGRESS'
                        )
                        THEN appointment_date
                    END ASC
                ")
                ->orderByRaw("
                    CASE
                        WHEN status IN (
                            'PENDING',
                            'CONFIRMED',
                            'IN_PROGRESS'
                        )
                        THEN appointment_time
                    END ASC
                ")
                ->orderByRaw("
                    CASE
                        WHEN status IN (
                            'COMPLETED',
                            'CANCELLED'
                        )
                        THEN appointment_date
                    END DESC
                ")
                ->orderByRaw("
                    CASE
                        WHEN status IN (
                            'COMPLETED',
                            'CANCELLED'
                        )
                        THEN appointment_time
                    END DESC
                ")
                ->orderByDesc(
                    'id'
                )
                ->get();


        return view(
            'staff.appointments.index',
            compact(
                'appointments',
                'statusFilter',
                'statusCounts',
                'totalAppointments'
            )
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
            'serviceOrder.invoice',
        ]);


        return view(
            'staff.appointments.show',
            compact(
                'appointment'
            )
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


        $appointment->load(
            'serviceOrder'
        );


        $validated =
            $request->validate(
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


        $allowedTransitions = [
            'PENDING' =>
                'CONFIRMED',

            'CONFIRMED' =>
                'IN_PROGRESS',

            'IN_PROGRESS' =>
                'COMPLETED',
        ];


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


        $expectedStatus =
            $allowedTransitions[
                $appointment->status
            ];


        if (
            $validated['status']
            !==
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


        if (
            $appointment->status
            === 'CONFIRMED'
            &&
            !$appointment->serviceOrder
        ) {
            return redirect()
                ->route(
                    'staff.appointments.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    'Vui lòng tạo phiếu bảo dưỡng trước khi bắt đầu thực hiện.'
                );
        }


        DB::transaction(
            function () use (
                $appointment,
                $validated
            ) {
                $lockedAppointment =
                    Appointment::query()
                        ->with(
                            'serviceOrder'
                        )
                        ->whereKey(
                            $appointment->id
                        )
                        ->lockForUpdate()
                        ->firstOrFail();


                $allowedTransitions = [
                    'PENDING' =>
                        'CONFIRMED',

                    'CONFIRMED' =>
                        'IN_PROGRESS',

                    'IN_PROGRESS' =>
                        'COMPLETED',
                ];


                $expectedStatus =
                    $allowedTransitions[
                        $lockedAppointment->status
                    ]
                    ?? null;


                if (
                    !$expectedStatus
                    ||
                    $validated['status']
                    !==
                    $expectedStatus
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Trạng thái lịch hẹn đã thay đổi. Vui lòng tải lại trang và thử lại.',
                    ]);
                }


                if (
                    $lockedAppointment->status
                    === 'CONFIRMED'
                    &&
                    !$lockedAppointment->serviceOrder
                ) {
                    throw ValidationException::withMessages([
                        'status' =>
                            'Vui lòng tạo phiếu bảo dưỡng trước khi bắt đầu thực hiện.',
                    ]);
                }


                $oldValues = [
                    'status' =>
                        $lockedAppointment->status,

                    'staff_note' =>
                        $lockedAppointment->staff_note,
                ];


                $newStaffNote =
                    !empty(
                        $validated['staff_note']
                    )
                        ? trim(
                            $validated['staff_note']
                        )
                        : $lockedAppointment
                            ->staff_note;


                $lockedAppointment->update([
                    'status' =>
                        $validated['status'],

                    'staff_note' =>
                        $newStaffNote,
                ]);


                ActivityLogger::log(
                    action:
                        'APPOINTMENT_STATUS_CHANGED',

                    description:
                        'Đã cập nhật lịch hẹn #'
                        .$lockedAppointment->id
                        .' từ '
                        .$oldValues['status']
                        .' sang '
                        .$lockedAppointment->status
                        .'.',

                    entity:
                        $lockedAppointment,

                    oldValues:
                        $oldValues,

                    newValues: [
                        'status' =>
                            $lockedAppointment->status,

                        'staff_note' =>
                            $lockedAppointment->staff_note,
                    ]
                );
            }
        );


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
     * Kiểm tra quyền STAFF / ADMIN.
     */
    private function authorizeStaff(): void
    {
        $user =
            Auth::user();


        if (
            !$user
            ||
            !$user->role
        ) {
            abort(
                403,
                'Bạn không có quyền truy cập.'
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