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
     * Hiển thị form thêm phương tiện.
     */
    public function create()
    {
        $brands = VehicleBrand::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('vehicles.create', compact('brands'));
    }

    /**
     * Lấy danh sách dòng xe theo hãng.
     */
    public function getModels($brandId)
    {
        $models = VehicleModel::where('brand_id', $brandId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'vehicle_type',
            ]);

        return response()->json($models);
    }

    /**
     * Lưu phương tiện mới.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->customer) {
            return back()
                ->withErrors([
                    'customer' => 'Không tìm thấy hồ sơ khách hàng của tài khoản.',
                ])
                ->withInput();
        }

        $validated = $request->validate(
            [
                'brand_id' => [
                    'required',
                    'exists:vehicle_brands,id',
                ],

                'model_id' => [
                    'required',
                    Rule::exists('vehicle_models', 'id')
                        ->where(function ($query) use ($request) {
                            $query->where('brand_id', $request->brand_id);
                        }),
                ],

                'license_plate' => [
                    'required',
                    'string',
                    'max:20',
                    'unique:vehicles,license_plate',
                ],

                'vin' => [
                    'nullable',
                    'string',
                    'max:50',
                    'unique:vehicles,vin',
                ],

                'manufacture_year' => [
                    'nullable',
                    'integer',
                    'min:1980',
                    'max:' . (date('Y') + 1),
                ],

                'color' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'fuel_type' => [
                    'nullable',
                    'string',
                    'max:50',
                ],

                'current_mileage' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'note' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],
            ],
            [
                'brand_id.required' => 'Vui lòng chọn hãng xe.',
                'brand_id.exists' => 'Hãng xe không hợp lệ.',

                'model_id.required' => 'Vui lòng chọn dòng xe.',
                'model_id.exists' => 'Dòng xe không hợp lệ hoặc không thuộc hãng đã chọn.',

                'license_plate.required' => 'Vui lòng nhập biển số xe.',
                'license_plate.unique' => 'Biển số xe này đã tồn tại trong hệ thống.',

                'vin.unique' => 'Số VIN này đã tồn tại trong hệ thống.',

                'manufacture_year.integer' => 'Năm sản xuất phải là số.',
                'manufacture_year.min' => 'Năm sản xuất không hợp lệ.',
                'manufacture_year.max' => 'Năm sản xuất không hợp lệ.',

                'current_mileage.required' => 'Vui lòng nhập số km hiện tại.',
                'current_mileage.integer' => 'Số km phải là số nguyên.',
                'current_mileage.min' => 'Số km không được nhỏ hơn 0.',
            ]
        );

        Vehicle::create([
            'customer_id' => $user->customer->id,
            'brand_id' => $validated['brand_id'],
            'model_id' => $validated['model_id'],
            'license_plate' => strtoupper(trim($validated['license_plate'])),
            'vin' => !empty($validated['vin'])
                ? strtoupper(trim($validated['vin']))
                : null,
            'manufacture_year' => $validated['manufacture_year'] ?? null,
            'color' => $validated['color'] ?? null,
            'fuel_type' => $validated['fuel_type'] ?? null,
            'current_mileage' => $validated['current_mileage'],
            'note' => $validated['note'] ?? null,
        ]);

        return redirect()
            ->route('vehicles.create')
            ->with('success', 'Thêm phương tiện thành công.');
    }
}