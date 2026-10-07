<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminActivityLogController extends Controller
{
    /**
     * Các role có thể xuất hiện
     * trong nhật ký nghiệp vụ.
     */
    private const ROLE_CODES = [
        'ADMIN',
        'STAFF',
        'TECHNICIAN',
        'CUSTOMER',
    ];


    /**
     * Danh sách nhật ký hoạt động.
     */
    public function index(
        Request $request
    ): View {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated =
            $request->validate([
                'q' => [
                    'nullable',
                    'string',
                    'max:120',
                ],

                'role' => [
                    'nullable',
                    'string',

                    Rule::in(
                        self::ROLE_CODES
                    ),
                ],

                'action' => [
                    'nullable',
                    'string',
                    'max:80',
                ],

                'date_from' => [
                    'nullable',
                    'date_format:Y-m-d',
                ],

                'date_to' => [
                    'nullable',
                    'date_format:Y-m-d',
                    'after_or_equal:date_from',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | NORMALIZED FILTERS
        |--------------------------------------------------------------------------
        */

        $keyword =
            preg_replace(
                '/\s+/u',
                ' ',
                trim(
                    (string)
                    (
                        $validated['q']
                        ?? ''
                    )
                )
            );


        $roleFilter =
            strtoupper(
                trim(
                    (string)
                    (
                        $validated['role']
                        ?? ''
                    )
                )
            );


        $actionFilter =
            strtoupper(
                trim(
                    (string)
                    (
                        $validated['action']
                        ?? ''
                    )
                )
            );


        $dateFrom =
            $validated[
                'date_from'
            ]
            ?? '';


        $dateTo =
            $validated[
                'date_to'
            ]
            ?? '';


        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $query =
            ActivityLog::query()
                ->with([
                    'user.role',
                ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($keyword !== '') {
            $query->where(
                function ($query) use (
                    $keyword
                ) {
                    $query
                        ->where(
                            'description',
                            'like',
                            '%'.$keyword.'%'
                        )
                        ->orWhere(
                            'action',
                            'like',
                            '%'.$keyword.'%'
                        )
                        ->orWhere(
                            'entity_type',
                            'like',
                            '%'.$keyword.'%'
                        )
                        ->orWhereHas(
                            'user',
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
                            }
                        );


                    if (
                        ctype_digit(
                            $keyword
                        )
                    ) {
                        $query
                            ->orWhere(
                                'id',
                                (int)
                                $keyword
                            )
                            ->orWhere(
                                'entity_id',
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

        if ($roleFilter !== '') {
            $query->where(
                'role_code',
                $roleFilter
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ACTION FILTER
        |--------------------------------------------------------------------------
        */

        if ($actionFilter !== '') {
            $query->where(
                'action',
                $actionFilter
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        if ($dateFrom !== '') {
            $query->whereDate(
                'created_at',
                '>=',
                $dateFrom
            );
        }


        if ($dateTo !== '') {
            $query->whereDate(
                'created_at',
                '<=',
                $dateTo
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $logs =
            $query
                ->orderByDesc(
                    'id'
                )
                ->paginate(
                    25
                )
                ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | FILTER OPTIONS
        |--------------------------------------------------------------------------
        */

        $actions =
            ActivityLog::query()
                ->whereNotNull(
                    'action'
                )
                ->distinct()
                ->orderBy(
                    'action'
                )
                ->pluck(
                    'action'
                );


        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalLogs =
            ActivityLog::query()
                ->count();


        $adminLogs =
            ActivityLog::query()
                ->where(
                    'role_code',
                    'ADMIN'
                )
                ->count();


        $staffLogs =
            ActivityLog::query()
                ->where(
                    'role_code',
                    'STAFF'
                )
                ->count();


        $technicianLogs =
            ActivityLog::query()
                ->where(
                    'role_code',
                    'TECHNICIAN'
                )
                ->count();


        return view(
            'admin.activity-logs.index',
            compact(
                'logs',
                'actions',
                'keyword',
                'roleFilter',
                'actionFilter',
                'dateFrom',
                'dateTo',
                'totalLogs',
                'adminLogs',
                'staffLogs',
                'technicianLogs'
            )
        );
    }
}