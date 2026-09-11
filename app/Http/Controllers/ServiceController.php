<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    /**
     * Danh sách dịch vụ đang hoạt động.
     */
    public function index()
    {
        $categories = ServiceCategory::with([
            'services' => function ($query) {
                $query->where('is_active', true)
                    ->orderBy('name');
            }
        ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'services.index',
            compact('categories')
        );
    }


    /**
     * Chi tiết một dịch vụ.
     */
    public function show(Service $service)
    {
        /**
         * Không cho truy cập dịch vụ
         * đang bị vô hiệu hóa.
         */
        if (!$service->is_active) {
            abort(404);
        }

        /**
         * Load nhóm dịch vụ.
         */
        $service->load('category');

        /**
         * Nếu nhóm dịch vụ bị khóa
         * thì cũng không hiển thị.
         */
        if (
            !$service->category ||
            !$service->category->is_active
        ) {
            abort(404);
        }

        return view(
            'services.show',
            compact('service')
        );
    }
}