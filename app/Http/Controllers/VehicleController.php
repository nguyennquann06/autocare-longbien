<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleBrand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Giới hạn ODO hợp lý để ngăn
     * dữ liệu bất thường do nhập sai.
     */
    private const MAX_MILEAGE = 5000000;


    /**
     * Hiển thị danh sách xe của
     * khách hàng đang đăng nhập.
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


        $vehicles = Vehicle::with([
            'brand',
            'vehicleModel',
        ])
            ->where(
                'customer_id',
                $user->customer->id
            )
            ->latest()
            ->get();


        return view(
            'vehicles.index',
            compact('vehicles')
        );
    }


    /**
     * Hiển thị form thêm phương tiện.
     */
    public function create()
    {
        $brands = VehicleBrand::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();


        return view(
            'vehicles.create',
            compact('brands')
        );
    }


    /**
     * Lưu phương tiện mới.
     */
    public function store(
        Request $request
    ) {
        $user = Auth::user();


        if (
            !$user ||
            !$user->customer
        ) {
            return back()
                ->withErrors([
                    'customer' =>
                        'Không tìm thấy hồ sơ khách hàng của tài khoản.',
                ])
                ->withInput();
        }


        $validated =
            $this->validateVehicle(
                $request
            );


        Vehicle::create([
            'customer_id' =>
                $user->customer->id,

            'brand_id' =>
                $validated['brand_id'],

            'model_id' =>
                $validated['model_id'],

            'license_plate' =>
                $validated['license_plate'],

            'vin' =>
                $validated['vin']
                ?? null,

            'manufacture_year' =>
                $validated[
                    'manufacture_year'
                ]
                ?? null,

            'color' =>
                $validated['color']
                ?? null,

            'fuel_type' =>
                $validated['fuel_type']
                ?? null,

            'current_mileage' =>
                $validated[
                    'current_mileage'
                ],

            'note' =>
                $validated['note']
                ?? null,
        ]);


        return redirect()
            ->route(
                'vehicles.index'
            )
            ->with(
                'success',
                'Thêm phương tiện thành công.'
            );
    }


    /**
     * Hiển thị chi tiết phương tiện.
     */
    public function show(
        Vehicle $vehicle
    ) {
        $this->authorizeVehicleOwner(
            $vehicle
        );


        $vehicle->load([
            'brand',
            'vehicleModel',
        ]);


        return view(
            'vehicles.show',
            compact('vehicle')
        );
    }


    /**
     * Hiển thị form chỉnh sửa.
     */
    public function edit(
        Vehicle $vehicle
    ) {
        $this->authorizeVehicleOwner(
            $vehicle
        );


        $brands = VehicleBrand::where(
            'is_active',
            true
        )
            ->orderBy('name')
            ->get();


        $vehicle->load([
            'brand',
            'vehicleModel',
        ]);


        return view(
            'vehicles.edit',
            compact(
                'vehicle',
                'brands'
            )
        );
    }


    /**
     * Cập nhật phương tiện.
     */
    public function update(
        Request $request,
        Vehicle $vehicle
    ) {
        $this->authorizeVehicleOwner(
            $vehicle
        );


        $validated =
            $this->validateVehicle(
                $request,
                $vehicle
            );


        $vehicle->update([
            'brand_id' =>
                $validated['brand_id'],

            'model_id' =>
                $validated['model_id'],

            'license_plate' =>
                $validated[
                    'license_plate'
                ],

            'vin' =>
                $validated['vin']
                ?? null,

            'manufacture_year' =>
                $validated[
                    'manufacture_year'
                ]
                ?? null,

            'color' =>
                $validated['color']
                ?? null,

            'fuel_type' =>
                $validated['fuel_type']
                ?? null,

            'current_mileage' =>
                $validated[
                    'current_mileage'
                ],

            'note' =>
                $validated['note']
                ?? null,
        ]);


        return redirect()
            ->route(
                'vehicles.show',
                $vehicle->id
            )
            ->with(
                'success',
                'Cập nhật phương tiện thành công.'
            );
    }


    /**
     * Xóa phương tiện.
     */
    public function destroy(
        Vehicle $vehicle
    ) {
        $this->authorizeVehicleOwner(
            $vehicle
        );


        $licensePlate =
            $vehicle->license_plate;


        $vehicle->delete();


        return redirect()
            ->route(
                'vehicles.index'
            )
            ->with(
                'success',
                'Đã xóa phương tiện '
                . $licensePlate
                . ' thành công.'
            );
    }


    /**
     * API lấy danh sách dòng xe
     * theo hãng đang hoạt động.
     */
    public function getModels(
        $brandId
    ) {
        $models =
            VehicleModel::where(
                'brand_id',
                $brandId
            )
                ->where(
                    'is_active',
                    true
                )
                ->orderBy('name')
                ->get([
                    'id',
                    'name',
                    'vehicle_type',
                ]);


        return response()->json(
            $models
        );
    }


    /**
     * Kiểm tra quyền sở hữu xe.
     */
    private function authorizeVehicleOwner(
        Vehicle $vehicle
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
            $vehicle->customer_id
            !==
            (int)
            $user->customer->id
        ) {
            abort(
                403,
                'Bạn không có quyền thao tác với phương tiện này.'
            );
        }
    }


    /**
     * Validate dữ liệu phương tiện.
     *
     * Dùng chung cho:
     * - Thêm xe
     * - Chỉnh sửa xe
     */
    private function validateVehicle(
        Request $request,
        ?Vehicle $vehicle = null
    ): array {
        /*
        |--------------------------------------------------------------------------
        | NORMALIZE INPUT
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'license_plate' =>
                $this->normalizeLicensePlate(
                    $request->input(
                        'license_plate'
                    )
                ),

            'vin' =>
                $this->normalizeVin(
                    $request->input(
                        'vin'
                    )
                ),

            'color' =>
                $this->normalizeShortText(
                    $request->input(
                        'color'
                    )
                ),

            'fuel_type' =>
                $this->normalizeShortText(
                    $request->input(
                        'fuel_type'
                    )
                ),

            'note' =>
                $this->normalizeNullableText(
                    $request->input(
                        'note'
                    )
                ),
        ]);


        /*
        |--------------------------------------------------------------------------
        | ODO
        |--------------------------------------------------------------------------
        |
        | Khi chỉnh sửa, ODO không được
        | nhỏ hơn số km đang lưu.
        |
        */

        $minimumMileage =
            $vehicle
                ? (int)
                    $vehicle
                        ->current_mileage
                : 0;


        $maximumYear =
            now()->year + 1;


        return $request->validate(
            [
                /*
                |--------------------------------------------------------------------------
                | BRAND
                |--------------------------------------------------------------------------
                */

                'brand_id' => [
                    'bail',
                    'required',
                    'integer',

                    Rule::exists(
                        'vehicle_brands',
                        'id'
                    )->where(
                        fn ($query) =>
                            $query->where(
                                'is_active',
                                true
                            )
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | MODEL
                |--------------------------------------------------------------------------
                */

                'model_id' => [
                    'bail',
                    'required',
                    'integer',

                    Rule::exists(
                        'vehicle_models',
                        'id'
                    )->where(
                        function (
                            $query
                        ) use (
                            $request
                        ) {
                            $query
                                ->where(
                                    'brand_id',
                                    $request
                                        ->input(
                                            'brand_id'
                                        )
                                )
                                ->where(
                                    'is_active',
                                    true
                                );
                        }
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | LICENSE PLATE
                |--------------------------------------------------------------------------
                |
                | Định dạng chuẩn hóa:
                |
                | 30H-123.45
                | 51K-999.99
                |
                */

                'license_plate' => [
                    'bail',
                    'required',
                    'string',
                    'max:12',
                    'regex:/^[0-9]{2}[A-Z]{1,2}-[0-9]{3}\.[0-9]{2}$/',

                    Rule::unique(
                        'vehicles',
                        'license_plate'
                    )->ignore(
                        $vehicle?->id
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | VIN
                |--------------------------------------------------------------------------
                |
                | VIN tiêu chuẩn:
                |
                | - đúng 17 ký tự
                | - chữ Latin + số
                | - không sử dụng I, O, Q
                |
                */

                'vin' => [
                    'bail',
                    'nullable',
                    'string',
                    'size:17',
                    'regex:/^[A-HJ-NPR-Z0-9]{17}$/',

                    Rule::unique(
                        'vehicles',
                        'vin'
                    )->ignore(
                        $vehicle?->id
                    ),
                ],


                /*
                |--------------------------------------------------------------------------
                | MANUFACTURE YEAR
                |--------------------------------------------------------------------------
                */

                'manufacture_year' => [
                    'bail',
                    'nullable',
                    'integer',
                    'min:1980',
                    'max:'
                    . $maximumYear,
                ],


                /*
                |--------------------------------------------------------------------------
                | COLOR
                |--------------------------------------------------------------------------
                */

                'color' => [
                    'bail',
                    'nullable',
                    'string',
                    'max:50',
                ],


                /*
                |--------------------------------------------------------------------------
                | FUEL TYPE
                |--------------------------------------------------------------------------
                */

                'fuel_type' => [
                    'bail',
                    'nullable',
                    'string',

                    Rule::in([
                        'Xăng',
                        'Dầu',
                        'Điện',
                        'Hybrid',
                    ]),
                ],


                /*
                |--------------------------------------------------------------------------
                | CURRENT MILEAGE
                |--------------------------------------------------------------------------
                */

                'current_mileage' => [
                    'bail',
                    'required',
                    'integer',
                    'min:'
                    . $minimumMileage,
                    'max:'
                    . self::MAX_MILEAGE,
                ],


                /*
                |--------------------------------------------------------------------------
                | NOTE
                |--------------------------------------------------------------------------
                */

                'note' => [
                    'bail',
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                /*
                |--------------------------------------------------------------------------
                | BRAND
                |--------------------------------------------------------------------------
                */

                'brand_id.required' =>
                    'Vui lòng chọn hãng xe.',

                'brand_id.integer' =>
                    'Hãng xe không hợp lệ.',

                'brand_id.exists' =>
                    'Hãng xe không tồn tại hoặc đã ngừng hoạt động.',


                /*
                |--------------------------------------------------------------------------
                | MODEL
                |--------------------------------------------------------------------------
                */

                'model_id.required' =>
                    'Vui lòng chọn dòng xe.',

                'model_id.integer' =>
                    'Dòng xe không hợp lệ.',

                'model_id.exists' =>
                    'Dòng xe không tồn tại, đã ngừng hoạt động hoặc không thuộc hãng xe đã chọn.',


                /*
                |--------------------------------------------------------------------------
                | LICENSE PLATE
                |--------------------------------------------------------------------------
                */

                'license_plate.required' =>
                    'Vui lòng nhập biển số xe.',

                'license_plate.string' =>
                    'Biển số xe không hợp lệ.',

                'license_plate.max' =>
                    'Biển số xe quá dài.',

                'license_plate.regex' =>
                    'Biển số xe không đúng định dạng. Ví dụ hợp lệ: 30H-123.45.',

                'license_plate.unique' =>
                    'Biển số xe này đã tồn tại trong hệ thống.',


                /*
                |--------------------------------------------------------------------------
                | VIN
                |--------------------------------------------------------------------------
                */

                'vin.string' =>
                    'Số VIN không hợp lệ.',

                'vin.size' =>
                    'Số VIN phải gồm đúng 17 ký tự.',

                'vin.regex' =>
                    'Số VIN chỉ được gồm chữ cái và chữ số hợp lệ, không sử dụng các ký tự I, O hoặc Q.',

                'vin.unique' =>
                    'Số VIN này đã tồn tại trong hệ thống.',


                /*
                |--------------------------------------------------------------------------
                | MANUFACTURE YEAR
                |--------------------------------------------------------------------------
                */

                'manufacture_year.integer' =>
                    'Năm sản xuất phải là số nguyên.',

                'manufacture_year.min' =>
                    'Năm sản xuất phải từ 1980 trở đi.',

                'manufacture_year.max' =>
                    'Năm sản xuất không được lớn hơn '
                    . $maximumYear
                    . '.',


                /*
                |--------------------------------------------------------------------------
                | COLOR
                |--------------------------------------------------------------------------
                */

                'color.string' =>
                    'Màu xe không hợp lệ.',

                'color.max' =>
                    'Tên màu xe không được vượt quá 50 ký tự.',


                /*
                |--------------------------------------------------------------------------
                | FUEL TYPE
                |--------------------------------------------------------------------------
                */

                'fuel_type.string' =>
                    'Loại nhiên liệu không hợp lệ.',

                'fuel_type.in' =>
                    'Loại nhiên liệu phải là Xăng, Dầu, Điện hoặc Hybrid.',


                /*
                |--------------------------------------------------------------------------
                | MILEAGE
                |--------------------------------------------------------------------------
                */

                'current_mileage.required' =>
                    'Vui lòng nhập ODO hiện tại của xe.',

                'current_mileage.integer' =>
                    'ODO phải là số nguyên, đơn vị km.',

                'current_mileage.min' =>
                    $vehicle
                        ? 'ODO mới không được nhỏ hơn ODO hiện tại đang lưu là '
                            . number_format(
                                $minimumMileage,
                                0,
                                ',',
                                '.'
                            )
                            . ' km.'
                        : 'ODO không được nhỏ hơn 0 km.',

                'current_mileage.max' =>
                    'ODO không được vượt quá '
                    . number_format(
                        self::MAX_MILEAGE,
                        0,
                        ',',
                        '.'
                    )
                    . ' km.',


                /*
                |--------------------------------------------------------------------------
                | NOTE
                |--------------------------------------------------------------------------
                */

                'note.string' =>
                    'Ghi chú không hợp lệ.',

                'note.max' =>
                    'Ghi chú không được vượt quá 1000 ký tự.',
            ]
        );
    }


    /**
     * Chuẩn hóa biển số.
     *
     * Ví dụ:
     *
     * 30h12345
     * 30H 12345
     * 30H-123.45
     *
     * đều thành:
     *
     * 30H-123.45
     */
    private function normalizeLicensePlate(
        mixed $value
    ): ?string {
        if (
            $value === null
        ) {
            return null;
        }


        $value =
            strtoupper(
                trim(
                    (string)
                    $value
                )
            );


        if ($value === '') {
            return null;
        }


        $compact =
            preg_replace(
                '/[\s\-.]+/',
                '',
                $value
            );


        if (
            preg_match(
                '/^([0-9]{2})([A-Z]{1,2})([0-9]{5})$/',
                $compact,
                $matches
            )
        ) {
            return
                $matches[1]
                . $matches[2]
                . '-'
                . substr(
                    $matches[3],
                    0,
                    3
                )
                . '.'
                . substr(
                    $matches[3],
                    3,
                    2
                );
        }


        return $value;
    }


    /**
     * Chuẩn hóa VIN.
     */
    private function normalizeVin(
        mixed $value
    ): ?string {
        if (
            $value === null
        ) {
            return null;
        }


        $value =
            strtoupper(
                preg_replace(
                    '/\s+/',
                    '',
                    trim(
                        (string)
                        $value
                    )
                )
                ?? ''
            );


        return $value !== ''
            ? $value
            : null;
    }


    /**
     * Chuẩn hóa text ngắn.
     */
    private function normalizeShortText(
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
     * Ghi chú:
     * giữ xuống dòng, chỉ bỏ khoảng
     * trắng thừa ở đầu và cuối.
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