<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Knowledge Source Registry
    |--------------------------------------------------------------------------
    |
    | Tất cả nguồn bên ngoài được AutoCare AI sử dụng
    | phải được khai báo tập trung tại đây.
    |
    | Thứ tự ưu tiên:
    |
    | MODEL_SPECIFIC
    |      ↓
    | BRAND_SPECIFIC
    |      ↓
    | GENERAL_MAINTENANCE
    |      ↓
    | NO_KNOWLEDGE
    |
    | Không được tự suy diễn thông số của hãng
    | khi nguồn không cung cấp thông tin đó.
    |
    */

    'accessed_at' =>
        '2026-10-10',


    /*
    |--------------------------------------------------------------------------
    | SUPPORTED BRANDS
    |--------------------------------------------------------------------------
    */

    'brands' => [

        /*
        |--------------------------------------------------------------------------
        | TOYOTA
        |--------------------------------------------------------------------------
        */

        'TOYOTA' => [

            'name' =>
                'Toyota',

            'country' =>
                'Nhật Bản',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Toyota Việt Nam',

                    'source_title' =>
                        'Dịch vụ bảo dưỡng định kỳ xe ô tô Toyota Việt Nam chính hãng',

                    'source_url' =>
                        'https://www.toyota.com.vn/dich-vu/dich-vu-bao-duong',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | HONDA
        |--------------------------------------------------------------------------
        */

        'HONDA' => [

            'name' =>
                'Honda',

            'country' =>
                'Nhật Bản',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Honda Việt Nam',

                    'source_title' =>
                        'Bảo dưỡng định kỳ ô tô',

                    'source_url' =>
                        'https://www.honda.com.vn/o-to/dich-vu-sau-ban-hang/bao-duong-dinh-ky',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

                'maintenance_policy' => [

                    'source_name' =>
                        'Honda Việt Nam',

                    'source_title' =>
                        'Chính sách bảo hành ô tô',

                    'source_url' =>
                        'https://www.honda.com.vn/o-to/dich-vu-sau-ban-hang/chinh-sach-bao-hanh',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

                'technical_faq' => [

                    'source_name' =>
                        'Honda Việt Nam',

                    'source_title' =>
                        'Câu hỏi thường gặp - Ô tô',

                    'source_url' =>
                        'https://www.honda.com.vn/cau-hoi-thuong-gap?category=o-to',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | MAZDA
        |--------------------------------------------------------------------------
        */

        'MAZDA' => [

            'name' =>
                'Mazda',

            'country' =>
                'Nhật Bản',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Mazda Việt Nam',

                    'source_title' =>
                        'Bảo dưỡng định kỳ',

                    'source_url' =>
                        'https://mazdamotors.vn/maintenance',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | MITSUBISHI
        |--------------------------------------------------------------------------
        */

        'MITSUBISHI' => [

            'name' =>
                'Mitsubishi',

            'country' =>
                'Nhật Bản',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Mitsubishi Motors Việt Nam',

                    'source_title' =>
                        'Bảo dưỡng định kỳ',

                    'source_url' =>
                        'https://www.mitsubishi-motors.com.vn/dich-vu/bao-duong-dinh-ky',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | NISSAN
        |--------------------------------------------------------------------------
        */

        'NISSAN' => [

            'name' =>
                'Nissan',

            'country' =>
                'Nhật Bản',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Nissan Việt Nam',

                    'source_title' =>
                        'Lịch bảo dưỡng',

                    'source_url' =>
                        'https://www.nissanvietnam.vn/lich-bao-duong-c5.html',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

                'warranty' => [

                    'source_name' =>
                        'Nissan Việt Nam',

                    'source_title' =>
                        'Chính sách bảo hành',

                    'source_url' =>
                        'https://www.nissanvietnam.vn/chinh-sach-bao-hanh-c4.html',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | HYUNDAI
        |--------------------------------------------------------------------------
        */

        'HYUNDAI' => [

            'name' =>
                'Hyundai',

            'country' =>
                'Hàn Quốc',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Hyundai Thành Công Việt Nam',

                    'source_title' =>
                        'Bảo dưỡng định kỳ',

                    'source_url' =>
                        'https://hyundai.thanhcong.vn/dich-vu-ky-thuat/bao-duong-dinh-ki',

                    'source_type' =>
                        'OEM_AUTHORIZED_DISTRIBUTOR',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | KIA
        |--------------------------------------------------------------------------
        */

        'KIA' => [

            'name' =>
                'Kia',

            'country' =>
                'Hàn Quốc',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Kia Việt Nam',

                    'source_title' =>
                        'Bảo dưỡng định kỳ Kia',

                    'source_url' =>
                        'https://www.kiavietnam.com.vn/bao-duong-dinh-ki',

                    'source_type' =>
                        'OEM_AUTHORIZED_DISTRIBUTOR',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | FORD
        |--------------------------------------------------------------------------
        */

        'FORD' => [

            'name' =>
                'Ford',

            'country' =>
                'Hoa Kỳ',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Ford Việt Nam',

                    'source_title' =>
                        'Bao lâu thì nên đưa xe đi bảo dưỡng?',

                    'source_url' =>
                        'https://www.ford.com.vn/support/how-tos/d%E1%BB%8Bch-v%E1%BB%A5/ph%E1%BB%A5-t%C3%B9ng-v%C3%A0-d%E1%BB%8Bch-v%E1%BB%A5/bao-l%C3%A2u-th%C3%AC-n%C3%AAn-%C4%91%C6%B0a-xe-%C4%91i-b%E1%BA%A3o-d%C6%B0%E1%BB%A1ng',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

                'maintenance_levels' => [

                    'source_name' =>
                        'Ford Việt Nam',

                    'source_title' =>
                        'Khác biệt giữa các cấp bảo dưỡng',

                    'source_url' =>
                        'https://www.ford.com.vn/support/how-tos/d%E1%BB%8Bch-v%E1%BB%A5/ph%E1%BB%A5-t%C3%B9ng-v%C3%A0-d%E1%BB%8Bch-v%E1%BB%A5/%C4%91%C3%A2u-l%C3%A0-s%E1%BB%B1-kh%C3%A1c-bi%E1%BB%87t-gi%E1%BB%AFa-b%E1%BA%A3o-d%C6%B0%E1%BB%A1ng-ch%C3%ADnh-v%C3%A0-b%E1%BA%A3o-d%C6%B0%E1%BB%A1ng-ph%E1%BB%A5',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

                'engine_oil' => [

                    'source_name' =>
                        'Ford Việt Nam',

                    'source_title' =>
                        'Bao lâu nên thay dầu động cơ?',

                    'source_url' =>
                        'https://www.ford.com.vn/support/how-tos/oil-change/oil-change-information/bao-lau-nen-thay-dau',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | VINFAST
        |--------------------------------------------------------------------------
        */

        'VINFAST' => [

            'name' =>
                'VinFast',

            'country' =>
                'Việt Nam',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'VinFast',

                    'source_title' =>
                        'Dịch vụ bảo dưỡng ô tô',

                    'source_url' =>
                        'https://vinfastauto.com/vn_vi/dich-vu-bao-duong-oto',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

                'ev_maintenance_2026' => [

                    'source_name' =>
                        'VinFast',

                    'source_title' =>
                        'VinFast tối ưu mốc bảo dưỡng, giảm chi phí vận hành cho khách hàng',

                    'source_url' =>
                        'https://vinfastauto.com/vn_vi/vinfast-toi-uu-moc-bao-duong-giam-chi-phi-van-hanh-cho-khach-hang',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'effective_from' =>
                        '2026-01-01',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | MERCEDES-BENZ
        |--------------------------------------------------------------------------
        */

        'MERCEDES_BENZ' => [

            'name' =>
                'Mercedes-Benz',

            'country' =>
                'Đức',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'maintenance' => [

                    'source_name' =>
                        'Mercedes-Benz Việt Nam',

                    'source_title' =>
                        'Dịch vụ và bảo dưỡng',

                    'source_url' =>
                        'https://www.mercedes-benz.com.vn/vi/passengercars/services/maintenance.html',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

                'owner_manual' => [

                    'source_name' =>
                        'Mercedes-Benz Việt Nam',

                    'source_title' =>
                        'Hướng dẫn sử dụng Mercedes-Benz',

                    'source_url' =>
                        'https://www.mercedes-benz.com.vn/vi/passengercars/services/manuals.html',

                    'source_type' =>
                        'OEM_OFFICIAL',

                    'verified' =>
                        true,

                ],

            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | BMW
        |--------------------------------------------------------------------------
        */

        'BMW' => [

            'name' =>
                'BMW',

            'country' =>
                'Đức',

            'scope' =>
                'BRAND_SPECIFIC',

            'sources' => [

                'service' => [

                    'source_name' =>
                        'BMW Việt Nam',

                    'source_title' =>
                        'Dịch vụ và sửa chữa BMW',

                    'source_url' =>
                        'https://www.bmw.vn/vi/services-workshop/',

                    'source_type' =>
                        'OEM_AUTHORIZED_DISTRIBUTOR',

                    'verified' =>
                        true,

                ],

                'condition_based_service' => [

                    'source_name' =>
                        'BMW Việt Nam',

                    'source_title' =>
                        'Proactive Care - Condition Based Service',

                    'source_url' =>
                        'https://www.bmw.vn/vi/services-workshop/bmw-proactive-care/',

                    'source_type' =>
                        'OEM_AUTHORIZED_DISTRIBUTOR',

                    'verified' =>
                        true,

                ],

            ],

        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | GENERAL MAINTENANCE SOURCES
    |--------------------------------------------------------------------------
    |
    | Đây là lớp kiến thức dùng chung cho tất cả hãng.
    |
    | Ví dụ:
    |
    | "Kia Seltos phanh kêu"
    |
    | Nếu chưa có tài liệu Kia riêng về tiếng kêu phanh,
    | chatbot có thể dùng nguồn BRAKES chung bên dưới.
    |
    | Tuy nhiên:
    |
    | - không tự tạo thông số của xe;
    | - không tự tạo chu kỳ riêng của hãng;
    | - không khẳng định chẩn đoán;
    | - ưu tiên tài liệu OEM nếu có.
    |
    */

    'general' => [

        /*
        |--------------------------------------------------------------------------
        | ENGINE OIL
        |--------------------------------------------------------------------------
        */

        'ENGINE_OIL' => [

            'topic' =>
                'engine_oil',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'Mobil',

            'source_title' =>
                'Motor Oil Product FAQ',

            'source_url' =>
                'https://www.mobil.com/en/lubricants/for-personal-vehicles/auto-care/all-about-oil/motor-oil-faqs',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'vai trò dầu động cơ',
                'dầu động cơ xuống cấp',
                'thay dầu',
                'lọc dầu',
                'độ nhớt',
                'điều kiện vận hành khắc nghiệt',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | TIRES
        |--------------------------------------------------------------------------
        */

        'TIRES' => [

            'topic' =>
                'tires',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'National Highway Traffic Safety Administration',

            'source_title' =>
                'Tire Safety Ratings and Awareness - TireWise',

            'source_url' =>
                'https://www.nhtsa.gov/vehicle-safety/tires',

            'source_type' =>
                'GOVERNMENT',

            'verified' =>
                true,

            'covers' => [
                'áp suất lốp',
                'TPMS',
                'độ mòn lốp',
                'đảo lốp',
                'cân bằng bánh xe',
                'cân chỉnh góc đặt bánh xe',
                'lốp rung',
                'lốp hư hỏng',
                'kích thước lốp',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | BRAKES
        |--------------------------------------------------------------------------
        */

        'BRAKES' => [

            'topic' =>
                'brakes',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'Bosch Automotive Aftermarket',

            'source_title' =>
                'Braking systems: safety & innovation',

            'source_url' =>
                'https://www.boschaftermarket.com/gb/en/parts/products/braking-systems/',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'má phanh',
                'đĩa phanh',
                'tiếng kêu khi phanh',
                'rung khi phanh',
                'mòn má phanh không đều',
                'dầu phanh',
                'kiểm tra hệ thống phanh',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | 12V BATTERY
        |--------------------------------------------------------------------------
        */

        'BATTERY_12V' => [

            'topic' =>
                'battery_12v',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'AAA',

            'source_title' =>
                'Car Battery Maintenance: A Comprehensive Guide',

            'source_url' =>
                'https://www.acg.aaa.com/connect/blogs/4c/auto/car-battery-maintenance-guide',

            'source_type' =>
                'AUTOMOTIVE_ORGANIZATION',

            'verified' =>
                true,

            'covers' => [
                'ắc quy 12V',
                'khó khởi động',
                'đèn yếu',
                'ăn mòn cực ắc quy',
                'kiểm tra điện áp',
                'dấu hiệu ắc quy yếu',
                'bảo dưỡng ắc quy',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | SPARK PLUGS
        |--------------------------------------------------------------------------
        */

        'SPARK_PLUGS' => [

            'topic' =>
                'spark_plugs',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'DENSO',

            'source_title' =>
                'Inspecting and Exchanging Plugs / Service Life',

            'source_url' =>
                'https://www.denso.com/global/en/products-and-services/automotive-service-parts-and-accessories/plug/basic/mente/',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'bugi',
                'khó khởi động',
                'máy rung',
                'bỏ máy',
                'hao nhiên liệu',
                'mất công suất',
                'kiểm tra bugi',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | COOLING SYSTEM
        |--------------------------------------------------------------------------
        */

        'COOLING_SYSTEM' => [

            'topic' =>
                'cooling_system',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'Gates',

            'source_title' =>
                'Protect The Ride - Automotive Cooling System',

            'source_url' =>
                'https://www.gates.com/us/en/innovations-and-solutions/automotive-and-on-highway-solutions/protect-the-ride.html',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'hệ thống làm mát',
                'két nước',
                'bơm nước',
                'ống nước',
                'thermostat',
                'nước làm mát',
                'quá nhiệt',
                'rò rỉ nước làm mát',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | ENGINE OVERHEATING
        |--------------------------------------------------------------------------
        */

        'OVERHEATING' => [

            'topic' =>
                'engine_overheating',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'AAA',

            'source_title' =>
                'Car Overheating: Causes and Solutions',

            'source_url' =>
                'https://ww1.aaa.com/autorepair/articles/car-overheating-8-causes-and-solutions',

            'source_type' =>
                'AUTOMOTIVE_ORGANIZATION',

            'verified' =>
                true,

            'covers' => [
                'động cơ quá nhiệt',
                'kim nhiệt độ cao',
                'hơi nước từ khoang máy',
                'thiếu nước làm mát',
                'thermostat',
                'bơm nước',
                'két nước',
                'xử lý an toàn khi quá nhiệt',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | BELTS / WATER PUMP / HOSES
        |--------------------------------------------------------------------------
        */

        'BELTS_HOSES' => [

            'topic' =>
                'belts_hoses',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'Gates',

            'source_title' =>
                'Tech Tip Bulletins',

            'source_url' =>
                'https://www.gates.com/us/en/knowledge-center/resource-library/tech-tip-bulletins.html',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'dây curoa',
                'dây đai phụ',
                'dây đai cam',
                'tăng đai',
                'puly',
                'bơm nước',
                'ống nước làm mát',
                'tiếng kêu dây đai',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | AIR CONDITIONING
        |--------------------------------------------------------------------------
        */

        'AIR_CONDITIONING' => [

            'topic' =>
                'air_conditioning',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'DENSO Vietnam',

            'source_title' =>
                'Car Air Conditioner',

            'source_url' =>
                'https://www.denso.com/vn/en/business/vietnam-parts-accessories/Automotive-Service-Parts/car-ac/',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'điều hòa ô tô',
                'máy nén điều hòa',
                'dàn nóng',
                'dàn lạnh',
                'môi chất lạnh',
                'điều hòa không mát',
                'hệ thống điều hòa',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | CABIN / AIR FILTERS
        |--------------------------------------------------------------------------
        */

        'FILTERS' => [

            'topic' =>
                'filters',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'DENSO',

            'source_title' =>
                'Automotive Service Parts and Accessories',

            'source_url' =>
                'https://www.denso.com/global/en/business/products-and-services/mobility/parts-accessories/',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'lọc gió động cơ',
                'lọc dầu',
                'lọc gió điều hòa',
                'lưu lượng gió điều hòa',
                'lọc bẩn',
                'bảo dưỡng bộ lọc',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | TRANSMISSION
        |--------------------------------------------------------------------------
        */

        'TRANSMISSION' => [

            'topic' =>
                'transmission',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'ZF Aftermarket',

            'source_title' =>
                'Transmission and Transmission Service for Passenger Cars',

            'source_url' =>
                'https://aftermarket.zf.com/en/aftermarket-portal/our-portfolio/passenger-cars/products/transmissions/',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'hộp số',
                'dầu hộp số',
                'hộp số tự động',
                'sang số bất thường',
                'bảo dưỡng hộp số',
                'thay dầu hộp số',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | SUSPENSION / STEERING / VIBRATION
        |--------------------------------------------------------------------------
        */

        'SUSPENSION_STEERING' => [

            'topic' =>
                'suspension_steering',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'Monroe',

            'source_title' =>
                'Signs of Bad Shocks & Struts',

            'source_url' =>
                'https://www.monroe.com/technical-resources/shocks-101/symptoms-worn-shock-struts.html',

            'source_type' =>
                'TECHNICAL_MANUFACTURER',

            'verified' =>
                true,

            'covers' => [
                'giảm xóc',
                'hệ thống treo',
                'xe bị nảy',
                'xe bị nghiêng',
                'chúi đầu khi phanh',
                'khó điều khiển',
                'rung xe',
                'mòn lốp không đều',
                'ổn định thân xe',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | WARNING LIGHTS
        |--------------------------------------------------------------------------
        */

        'WARNING_LIGHTS' => [

            'topic' =>
                'warning_lights',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'AAA',

            'source_title' =>
                'The Check Engine Light: Common Causes and How To Fix It',

            'source_url' =>
                'https://www.aaa.com/autorepair/articles/check-engine-light',

            'source_type' =>
                'AUTOMOTIVE_ORGANIZATION',

            'verified' =>
                true,

            'covers' => [
                'check engine',
                'đèn động cơ',
                'đèn cảnh báo',
                'lỗi động cơ',
                'lỗi hệ thống khí thải',
                'kiểm tra xe khi có đèn cảnh báo',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | FLOOD DAMAGE
        |--------------------------------------------------------------------------
        */

        'FLOOD_DAMAGE' => [

            'topic' =>
                'flood_damage',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'AAA',

            'source_title' =>
                'Flood Damaged Cars',

            'source_url' =>
                'https://ww1.aaa.com/autorepair/articles/flood-damaged-cars',

            'source_type' =>
                'AUTOMOTIVE_ORGANIZATION',

            'verified' =>
                true,

            'covers' => [
                'xe ngập nước',
                'xe bị ngập',
                'nước vào xe',
                'không khởi động xe sau ngập',
                'kiểm tra xe sau ngập',
                'hư hỏng do nước',
            ],

        ],


        /*
        |--------------------------------------------------------------------------
        | ELECTRIC VEHICLE
        |--------------------------------------------------------------------------
        */

        'ELECTRIC_VEHICLE' => [

            'topic' =>
                'electric_vehicle',

            'scope' =>
                'GENERAL_MAINTENANCE',

            'source_name' =>
                'U.S. Department of Energy - Alternative Fuels Data Center',

            'source_title' =>
                'Maintenance and Safety of Electric Vehicles',

            'source_url' =>
                'https://afdc.energy.gov/vehicles/electric-maintenance',

            'source_type' =>
                'GOVERNMENT',

            'verified' =>
                true,

            'covers' => [
                'xe điện',
                'pin cao áp',
                'động cơ điện',
                'phanh tái sinh',
                'làm mát pin',
                'bảo dưỡng xe điện',
                'an toàn điện cao áp',
            ],

        ],

    ],


    /*
    |--------------------------------------------------------------------------
    | RETRIEVAL PRIORITY
    |--------------------------------------------------------------------------
    */

    'retrieval_priority' => [

        'MODEL_SPECIFIC',

        'BRAND_SPECIFIC',

        'GENERAL_MAINTENANCE',

        'AUTOCARE_SERVICE',

    ],


    /*
    |--------------------------------------------------------------------------
    | SOURCE POLICY
    |--------------------------------------------------------------------------
    */

    'policy' => [

        /*
         * Không dùng blog cá nhân,
         * Facebook hoặc diễn đàn
         * làm nguồn chính.
         */

        'allowed_primary_types' => [

            'OEM_OFFICIAL',

            'OEM_AUTHORIZED_DISTRIBUTOR',

            'GOVERNMENT',

            'TECHNICAL_STANDARD',

            'TECHNICAL_MANUFACTURER',

            'AUTOMOTIVE_ORGANIZATION',

        ],


        /*
         * Nếu có thông tin riêng của model,
         * MODEL_SPECIFIC được ưu tiên cao nhất.
         */

        'prefer_model_specific' =>
            true,


        /*
         * Nếu không có model nhưng có hướng dẫn
         * chính thức của hãng, ưu tiên hãng.
         */

        'prefer_brand_specific' =>
            true,


        /*
         * Nếu hãng không có hướng dẫn riêng
         * cho vấn đề kỹ thuật thông thường,
         * cho phép dùng kiến thức chung có nguồn.
         */

        'allow_general_fallback' =>
            true,


        /*
         * Không cho phép AI tự tạo:
         *
         * - chu kỳ bảo dưỡng;
         * - dung tích dầu;
         * - loại dầu cụ thể của model;
         * - áp suất lốp cụ thể;
         * - thông số kỹ thuật;
         * - giá;
         * - dữ liệu khách hàng.
         */

        'allow_unsourced_facts' =>
            false,


        /*
         * Không chẩn đoán chắc chắn chỉ từ
         * mô tả bằng văn bản.
         */

        'allow_definitive_diagnosis' =>
            false,


        /*
         * Với vấn đề có nguy cơ mất an toàn,
         * câu trả lời phải ưu tiên hướng dẫn
         * an toàn và khuyến nghị kiểm tra.
         */

        'safety_first' =>
            true,

    ],

];