<?php

namespace Database\Seeders;

use App\Models\KnowledgeDocument;
use App\Models\Service;
use Illuminate\Database\Seeder;
use RuntimeException;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        $accessedAt = (string) config(
            'knowledge_sources.accessed_at',
            now()->toDateString()
        );

        $appUrl = rtrim(
            (string) config(
                'app.url',
                'http://127.0.0.1:8000'
            ),
            '/'
        );

        /*
        |--------------------------------------------------------------------------
        | SOURCE HELPERS
        |--------------------------------------------------------------------------
        */

        $brandSourceMetadata = function (
            string $brandCode,
            string $sourceKey,
            array $extra = []
        ) use (
            $accessedAt
        ): array {
            $brand = config(
                "knowledge_sources.brands.{$brandCode}"
            );

            if (!is_array($brand)) {
                throw new RuntimeException(
                    "Không tìm thấy cấu hình hãng: {$brandCode}"
                );
            }

            $source = data_get(
                $brand,
                "sources.{$sourceKey}"
            );

            if (!is_array($source)) {
                throw new RuntimeException(
                    "Không tìm thấy nguồn {$sourceKey} của hãng {$brandCode}"
                );
            }

            return array_merge(
                [
                    'scope' =>
                        'BRAND_SPECIFIC',

                    'brand' =>
                        $brand['name']
                        ?? null,

                    'model' =>
                        null,

                    'source_name' =>
                        $source['source_name']
                        ?? null,

                    'source_title' =>
                        $source['source_title']
                        ?? null,

                    'source_url' =>
                        $source['source_url']
                        ?? null,

                    'reference_type' =>
                        $source['source_type']
                        ?? null,

                    'accessed_at' =>
                        $accessedAt,

                    'source_verified' =>
                        (bool) (
                            $source['verified']
                            ?? false
                        ),
                ],
                $extra
            );
        };


        $generalSourceMetadata = function (
            string $sourceKey,
            array $extra = []
        ) use (
            $accessedAt
        ): array {
            $source = config(
                "knowledge_sources.general.{$sourceKey}"
            );

            if (!is_array($source)) {
                throw new RuntimeException(
                    "Không tìm thấy nguồn kỹ thuật chung: {$sourceKey}"
                );
            }

            return array_merge(
                [
                    'topic' =>
                        $source['topic']
                        ?? null,

                    'scope' =>
                        'GENERAL_MAINTENANCE',

                    'brand' =>
                        null,

                    'model' =>
                        null,

                    'source_name' =>
                        $source['source_name']
                        ?? null,

                    'source_title' =>
                        $source['source_title']
                        ?? null,

                    'source_url' =>
                        $source['source_url']
                        ?? null,

                    'reference_type' =>
                        $source['source_type']
                        ?? null,

                    'accessed_at' =>
                        $accessedAt,

                    'source_verified' =>
                        (bool) (
                            $source['verified']
                            ?? false
                        ),
                ],
                $extra
            );
        };


        $internalMetadata = function (
            string $topic,
            string $sourceTitle,
            string $sourceUrl,
            array $extra = []
        ) use (
            $accessedAt
        ): array {
            return array_merge(
                [
                    'topic' =>
                        $topic,

                    'scope' =>
                        'AUTOCARE',

                    'brand' =>
                        null,

                    'model' =>
                        null,

                    'source_name' =>
                        'AutoCare Long Biên',

                    'source_title' =>
                        $sourceTitle,

                    'source_url' =>
                        $sourceUrl,

                    'reference_type' =>
                        'AUTOCARE_INTERNAL',

                    'accessed_at' =>
                        $accessedAt,

                    'source_verified' =>
                        true,
                ],
                $extra
            );
        };


        /*
        |--------------------------------------------------------------------------
        | SMART UPSERT
        |--------------------------------------------------------------------------
        |
        | Nếu nội dung và metadata không đổi,
        | giữ nguyên embedding cũ.
        |
        | Chỉ reset embedding khi dữ liệu
        | phục vụ retrieval thực sự thay đổi.
        |
        */

        $storeDocument = function (
            array $identity,
            array $attributes
        ): KnowledgeDocument {
            $document =
                KnowledgeDocument::query()
                    ->firstOrNew(
                        $identity
                    );

            $isNew =
                !$document->exists;

            $contentChanged =
                $isNew
                ||
                (string) $document->content
                    !==
                (string) (
                    $attributes['content']
                    ?? ''
                );

            $titleChanged =
                $isNew
                ||
                (string) $document->title
                    !==
                (string) (
                    $attributes['title']
                    ?? $document->title
                );

            $sourceIdChanged =
                $isNew
                ||
                $document->source_id
                    !=
                (
                    $attributes['source_id']
                    ?? null
                );

            $metadataChanged =
                $isNew
                ||
                (
                    is_array(
                        $document->metadata
                    )
                        ? $document->metadata
                        : []
                )
                    !=
                (
                    $attributes['metadata']
                    ?? []
                );

            $document->fill(
                $attributes
            );

            if (
                $contentChanged
                ||
                $titleChanged
                ||
                $sourceIdChanged
                ||
                $metadataChanged
            ) {
                $document->embedding =
                    null;
            }

            $document->is_active =
                true;

            $document->save();

            return $document;
        };


        /*
        |--------------------------------------------------------------------------
        | AUTOCARE INTERNAL KNOWLEDGE
        |--------------------------------------------------------------------------
        */

        $internalDocuments = [

            [
                'source_type' =>
                    'BUSINESS_INFO',

                'title' =>
                    'Giới thiệu AutoCare Long Biên',

                'content' =>
                    'AutoCare Long Biên là hệ thống thông tin quản lý và hỗ trợ dịch vụ bảo dưỡng ô tô tại khu vực Long Biên, Hà Nội. Hệ thống hỗ trợ khách hàng quản lý phương tiện, đặt lịch bảo dưỡng, theo dõi lịch sử bảo dưỡng, theo dõi hóa đơn và sử dụng AutoCare AI để tra cứu thông tin phù hợp.',

                'metadata' =>
                    $internalMetadata(
                        'about',
                        'Trang chủ AutoCare Long Biên',
                        $appUrl
                    ),
            ],

            [
                'source_type' =>
                    'FAQ',

                'title' =>
                    'Quy trình đặt lịch bảo dưỡng',

                'content' =>
                    'Khách hàng đăng nhập vào AutoCare Long Biên, chọn phương tiện thuộc tài khoản của mình, chọn dịch vụ, ngày và giờ mong muốn rồi gửi yêu cầu đặt lịch. Lịch hẹn được nhân viên tiếp nhận và xử lý theo quy trình của hệ thống.',

                'metadata' =>
                    $internalMetadata(
                        'appointment',
                        'Chức năng đặt lịch bảo dưỡng',
                        $appUrl
                        . '/appointments/create'
                    ),
            ],

            [
                'source_type' =>
                    'FAQ',

                'title' =>
                    'Quy trình bảo dưỡng tại AutoCare',

                'content' =>
                    'Sau khi lịch hẹn được xác nhận, nhân viên có thể tiếp nhận xe và tạo phiếu bảo dưỡng. Phiếu được phân công cho kỹ thuật viên. Kỹ thuật viên cập nhật tiến độ từng hạng mục. Khi toàn bộ công việc hoàn thành, phiếu được hoàn tất và nhân viên có thể lập hóa đơn.',

                'metadata' =>
                    $internalMetadata(
                        'maintenance_process',
                        'Quy trình nghiệp vụ bảo dưỡng AutoCare',
                        $appUrl
                    ),
            ],

            [
                'source_type' =>
                    'FAQ',

                'title' =>
                    'Lịch sử bảo dưỡng của khách hàng',

                'content' =>
                    'Khách hàng đã đăng nhập có thể xem lịch sử bảo dưỡng của các phương tiện thuộc chính tài khoản của mình. Lịch sử có thể gồm phiếu bảo dưỡng, các hạng mục đã thực hiện, số ODO, kỹ thuật viên, thời điểm hoàn thành và chi phí liên quan.',

                'metadata' =>
                    $internalMetadata(
                        'maintenance_history',
                        'Chức năng lịch sử bảo dưỡng',
                        $appUrl
                        . '/maintenance-history'
                    ),
            ],

            [
                'source_type' =>
                    'FAQ',

                'title' =>
                    'Hóa đơn và thanh toán AutoCare',

                'content' =>
                    'Sau khi phiếu bảo dưỡng hoàn thành, nhân viên có thể lập hóa đơn. Hóa đơn lưu thông tin dịch vụ và phụ tùng tại thời điểm lập. Hệ thống hỗ trợ các phương thức thanh toán được cấu hình trong AutoCare như tiền mặt, chuyển khoản ngân hàng hoặc thẻ.',

                'metadata' =>
                    $internalMetadata(
                        'invoice',
                        'Chức năng hóa đơn AutoCare',
                        $appUrl
                        . '/my-invoices'
                    ),
            ],

            [
                'source_type' =>
                    'FAQ',

                'title' =>
                    'Quản lý phương tiện AutoCare',

                'content' =>
                    'Khách hàng có thể thêm và quản lý phương tiện của mình trên AutoCare. Dữ liệu có thể gồm hãng, dòng xe, biển số, VIN, năm sản xuất, màu xe, loại nhiên liệu và số kilomet hiện tại. ODO nên được cập nhật chính xác để hỗ trợ theo dõi bảo dưỡng.',

                'metadata' =>
                    $internalMetadata(
                        'vehicle',
                        'Chức năng quản lý phương tiện',
                        $appUrl
                        . '/vehicles'
                    ),
            ],

            [
                'source_type' =>
                    'FAQ',

                'title' =>
                    'Nguyên tắc gợi ý bảo dưỡng AutoCare',

                'content' =>
                    'AutoCare có thể kết hợp ODO hiện tại, lịch sử bảo dưỡng, dữ liệu dịch vụ và kho tri thức để hỗ trợ gợi ý. Với thông số riêng của từng hãng hoặc dòng xe, hệ thống phải ưu tiên tài liệu của nhà sản xuất và không tự tạo thông số chưa có nguồn.',

                'metadata' =>
                    $internalMetadata(
                        'recommendation',
                        'Nguyên tắc hỗ trợ bảo dưỡng AutoCare',
                        $appUrl
                        . '/chat'
                    ),
            ],

            [
                'source_type' =>
                    'AI_POLICY',

                'title' =>
                    'Phạm vi tư vấn của AutoCare AI',

                'content' =>
                    'AutoCare AI hỗ trợ kiến thức bảo dưỡng, thông tin dịch vụ, quy trình AutoCare và dữ liệu cá nhân mà người dùng có quyền truy cập. Chatbot không được tự tạo thông số kỹ thuật, không được chẩn đoán chắc chắn chỉ từ mô tả bằng văn bản và không thay thế kiểm tra trực tiếp đối với tình trạng nguy hiểm hoặc chưa đủ dữ liệu.',

                'metadata' =>
                    $internalMetadata(
                        'assistant_scope',
                        'Phạm vi hoạt động AutoCare AI',
                        $appUrl
                        . '/chat'
                    ),
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | BRAND-SPECIFIC KNOWLEDGE
        |--------------------------------------------------------------------------
        */

        $brandDocuments = [

            /*
            |----------------------------------------------------------------------
            | TOYOTA
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'TOYOTA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Toyota - Kiểm tra ban đầu tại 1.000 km',

                'content' =>
                    'Toyota Việt Nam công bố mốc kiểm tra ban đầu tại 1.000 km. Xe được kiểm tra các hạng mục theo tiêu chuẩn, tình trạng dung dịch, tình trạng vận hành và được tư vấn bảo dưỡng phù hợp.',

                'topic' =>
                    'initial_inspection',

                'keywords' => [
                    'Toyota',
                    '1000 km',
                    'kiểm tra ban đầu',
                    'xe mới',
                ],
            ],

            [
                'brand' =>
                    'TOYOTA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Toyota - Chu kỳ bảo dưỡng 5.000 km hoặc 6 tháng',

                'content' =>
                    'Toyota Việt Nam sử dụng mốc 5.000 km hoặc 6 tháng, tùy điều kiện nào đến trước, trong lịch bảo dưỡng định kỳ. Hạng mục cụ thể cần đối chiếu với cấp bảo dưỡng và dòng xe tương ứng.',

                'topic' =>
                    'maintenance_interval',

                'keywords' => [
                    'Toyota',
                    '5000 km',
                    '6 tháng',
                    'bao lâu bảo dưỡng',
                ],
            ],

            [
                'brand' =>
                    'TOYOTA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Toyota - Bảo dưỡng cấp nhỏ',

                'content' =>
                    'Theo Toyota Việt Nam, bảo dưỡng cấp nhỏ được thực hiện lần đầu ở mốc 5.000 km, sau đó tại các mốc cách 10.000 km như 15.000 km và 25.000 km, hoặc sau 6 tháng tùy điều kiện nào đến trước. Nội dung có thể gồm kiểm tra động cơ, gầm và thân xe, hệ thống đánh lửa và điện thân xe.',

                'topic' =>
                    'minor_maintenance',

                'keywords' => [
                    'Toyota',
                    'cấp nhỏ',
                    '5000 km',
                    '15000 km',
                    '25000 km',
                ],
            ],

            [
                'brand' =>
                    'TOYOTA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Toyota - Bảo dưỡng cấp lớn 40.000 km',

                'content' =>
                    'Toyota Việt Nam công bố bảo dưỡng cấp lớn tại 40.000 km, sau đó lặp lại mỗi 40.000 km như 80.000 km và 120.000 km, hoặc sau 48 tháng tùy điều kiện nào đến trước. Các hệ thống kiểm tra bao gồm động cơ, nhiên liệu, đánh lửa, điều hòa, làm mát, gầm, điện và phanh.',

                'topic' =>
                    'major_maintenance',

                'keywords' => [
                    'Toyota',
                    '40000 km',
                    '48 tháng',
                    'cấp lớn',
                    'phanh',
                    'điều hòa',
                    'làm mát',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | HONDA
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'HONDA',

                'source' =>
                    'maintenance_policy',

                'title' =>
                    'Honda - Chu kỳ bảo dưỡng 5.000 km hoặc 6 tháng',

                'content' =>
                    'Honda Việt Nam áp dụng nguyên tắc bảo dưỡng định kỳ theo mốc 5.000 km hoặc 6 tháng, tùy điều kiện nào đến trước. Khi cần xác định chính xác hạng mục của một dòng xe cụ thể, cần đối chiếu thêm sách hướng dẫn và lịch bảo dưỡng của xe.',

                'topic' =>
                    'maintenance_interval',

                'keywords' => [
                    'Honda',
                    '5000 km',
                    '6 tháng',
                    'bao lâu bảo dưỡng',
                ],
            ],

            [
                'brand' =>
                    'HONDA',

                'source' =>
                    'technical_faq',

                'title' =>
                    'Honda - Chu kỳ thay dầu động cơ',

                'content' =>
                    'Honda Việt Nam hướng dẫn đối với ô tô Honda, dầu động cơ nên được thay sau mỗi 5.000 km hoặc 6 tháng, tùy điều kiện nào đến trước. Khi lựa chọn loại dầu cụ thể cần tuân thủ yêu cầu của đúng dòng xe và động cơ.',

                'topic' =>
                    'engine_oil',

                'keywords' => [
                    'Honda',
                    'dầu động cơ',
                    'dầu máy',
                    '5000 km',
                    '6 tháng',
                ],
            ],

            [
                'brand' =>
                    'HONDA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Honda - Các nhóm hạng mục kiểm tra định kỳ',

                'content' =>
                    'Trong nội dung bảo dưỡng định kỳ, Honda Việt Nam liệt kê các nhóm kiểm tra như dầu động cơ, lọc dầu, dung dịch làm mát, dầu phanh, dây đai, lọc gió, hệ thống phanh, hệ thống treo, lốp và các chức năng khác trên xe. Chu kỳ chi tiết phụ thuộc lịch bảo dưỡng của xe.',

                'topic' =>
                    'maintenance_items',

                'keywords' => [
                    'Honda',
                    'dầu phanh',
                    'lốp',
                    'lọc gió',
                    'hệ thống treo',
                    'nước làm mát',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | MAZDA
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'MAZDA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Mazda - Kiểm tra 1.000 km đầu tiên',

                'content' =>
                    'Mazda Việt Nam có cấp kiểm tra và bảo dưỡng dành cho 1.000 km đầu tiên. Đây là giai đoạn kiểm tra ban đầu nhằm bảo đảm các hệ thống của xe hoạt động phù hợp sau thời gian sử dụng đầu tiên.',

                'topic' =>
                    'initial_inspection',

                'keywords' => [
                    'Mazda',
                    '1000 km',
                    'kiểm tra đầu tiên',
                ],
            ],

            [
                'brand' =>
                    'MAZDA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Mazda - Bảo dưỡng cấp 1 tại 3 tháng hoặc 5.000 km',

                'content' =>
                    'Mazda Việt Nam công bố bảo dưỡng cấp 1 ở khoảng 3 tháng hoặc 5.000 km. Đây là cấp bảo dưỡng định kỳ ban đầu sau giai đoạn kiểm tra xe mới.',

                'topic' =>
                    'maintenance_level_1',

                'keywords' => [
                    'Mazda',
                    '5000 km',
                    '3 tháng',
                    'cấp 1',
                ],
            ],

            [
                'brand' =>
                    'MAZDA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Mazda - Các cấp bảo dưỡng định kỳ',

                'content' =>
                    'Mazda tổ chức bảo dưỡng theo nhiều cấp, từ kiểm tra 1.000 km đầu tiên, cấp 1 đến các cấp 2, 3, 4 và cấp 5. Hạng mục và thời điểm chính xác cần được đối chiếu với lịch bảo dưỡng áp dụng cho xe cụ thể.',

                'topic' =>
                    'maintenance_levels',

                'keywords' => [
                    'Mazda',
                    'cấp 1',
                    'cấp 2',
                    'cấp 3',
                    'cấp 4',
                    'cấp 5',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | MITSUBISHI
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'MITSUBISHI',

                'source' =>
                    'maintenance',

                'title' =>
                    'Mitsubishi - Chu kỳ bảo dưỡng 5.000 km hoặc 3 tháng',

                'content' =>
                    'Mitsubishi Motors Việt Nam khuyến nghị bảo dưỡng định kỳ mỗi 5.000 km hoặc 3 tháng sử dụng, tùy điều kiện nào đến trước, đối với các xe thuộc lịch bảo dưỡng thông thường.',

                'topic' =>
                    'maintenance_interval',

                'keywords' => [
                    'Mitsubishi',
                    '5000 km',
                    '3 tháng',
                    'bao lâu bảo dưỡng',
                ],
            ],

            [
                'brand' =>
                    'MITSUBISHI',

                'source' =>
                    'maintenance',

                'title' =>
                    'Mitsubishi All New Triton 4N16 - Chu kỳ 10.000 km hoặc 6 tháng',

                'content' =>
                    'Mitsubishi Motors Việt Nam nêu riêng All New Triton sử dụng động cơ 4N16 có chu kỳ bảo dưỡng 10.000 km hoặc 6 tháng, tùy điều kiện nào đến trước.',

                'topic' =>
                    'maintenance_interval',

                'keywords' => [
                    'Mitsubishi',
                    'Triton',
                    '4N16',
                    '10000 km',
                    '6 tháng',
                ],

                'model' =>
                    'All New Triton 4N16',
            ],

            [
                'brand' =>
                    'MITSUBISHI',

                'source' =>
                    'maintenance',

                'title' =>
                    'Mitsubishi - Các nhóm hạng mục bảo dưỡng',

                'content' =>
                    'Mitsubishi Motors Việt Nam mô tả bảo dưỡng gồm thay thế định kỳ các vật tư hao mòn như dầu nhớt và các bộ lọc, đồng thời kiểm tra các hệ thống quan trọng như phanh, treo, lái và các hệ thống an toàn.',

                'topic' =>
                    'maintenance_items',

                'keywords' => [
                    'Mitsubishi',
                    'dầu nhớt',
                    'lọc dầu',
                    'lọc gió',
                    'phanh',
                    'treo',
                    'lái',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | NISSAN
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'NISSAN',

                'source' =>
                    'warranty',

                'title' =>
                    'Nissan - Bảo dưỡng đầu tiên 1.000 km hoặc 1 tháng',

                'content' =>
                    'Nissan Việt Nam quy định lần bảo dưỡng định kỳ đầu tiên ở 1.000 km hoặc 1 tháng, tùy điều kiện nào đến trước.',

                'topic' =>
                    'initial_inspection',

                'keywords' => [
                    'Nissan',
                    '1000 km',
                    '1 tháng',
                    'bảo dưỡng đầu tiên',
                ],
            ],

            [
                'brand' =>
                    'NISSAN',

                'source' =>
                    'warranty',

                'title' =>
                    'Nissan - Các lần bảo dưỡng tiếp theo 5.000 km hoặc 3 tháng',

                'content' =>
                    'Nissan Việt Nam nêu các lần bảo dưỡng sau lần đầu được thực hiện mỗi 5.000 km hoặc 3 tháng, tùy điều kiện nào đến trước. Chi tiết hạng mục cần xem sách hướng dẫn và sổ bảo hành của xe.',

                'topic' =>
                    'maintenance_interval',

                'keywords' => [
                    'Nissan',
                    '5000 km',
                    '3 tháng',
                    'bao lâu bảo dưỡng',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | HYUNDAI
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'HYUNDAI',

                'source' =>
                    'maintenance',

                'title' =>
                    'Hyundai - Bảo dưỡng đầu tiên 1.000 km hoặc 1 tháng',

                'content' =>
                    'Hyundai Thành Công Việt Nam công bố lần bảo dưỡng đầu tiên khi xe đạt 1.000 km hoặc đã sử dụng được 1 tháng, tùy điều kiện nào đến trước.',

                'topic' =>
                    'initial_inspection',

                'keywords' => [
                    'Hyundai',
                    '1000 km',
                    '1 tháng',
                    'bảo dưỡng đầu tiên',
                ],
            ],

            [
                'brand' =>
                    'HYUNDAI',

                'source' =>
                    'maintenance',

                'title' =>
                    'Hyundai - Bảo dưỡng tiêu chuẩn 5.000 km hoặc 6 tháng',

                'content' =>
                    'Hyundai Thành Công Việt Nam công bố bảo dưỡng cấp tiêu chuẩn sau mỗi 5.000 km hoặc 6 tháng, tùy điều kiện nào đến trước.',

                'topic' =>
                    'maintenance_interval',

                'keywords' => [
                    'Hyundai',
                    '5000 km',
                    '6 tháng',
                    'bao lâu bảo dưỡng',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | KIA
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'KIA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Kia - Các hạng mục kiểm tra bảo dưỡng cơ bản',

                'content' =>
                    'Trong bảo dưỡng định kỳ, Kia liệt kê các nhóm kiểm tra cơ bản như hệ thống phanh, lái và treo, gầm xe, các liên kết, lốp và áp suất lốp, hệ thống điện, cảnh báo và các loại dung dịch trên xe.',

                'topic' =>
                    'maintenance_items',

                'keywords' => [
                    'Kia',
                    'phanh',
                    'lái',
                    'treo',
                    'gầm',
                    'lốp',
                    'áp suất lốp',
                    'dung dịch',
                ],
            ],

            [
                'brand' =>
                    'KIA',

                'source' =>
                    'maintenance',

                'title' =>
                    'Kia - Bảo dưỡng được tổ chức theo nhiều cấp',

                'content' =>
                    'Kia tổ chức chương trình bảo dưỡng định kỳ theo các cấp khác nhau. Cấp bảo dưỡng và các hạng mục áp dụng phụ thuộc dòng xe, quãng đường, thời gian và hướng dẫn kỹ thuật tương ứng. Chatbot không được tự suy ra một mốc kilomet chung cho mọi xe Kia nếu chưa có tài liệu của dòng xe.',

                'topic' =>
                    'maintenance_levels',

                'keywords' => [
                    'Kia',
                    'cấp bảo dưỡng',
                    'bảo dưỡng định kỳ',
                    'lịch bảo dưỡng',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | FORD
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'FORD',

                'source' =>
                    'engine_oil',

                'title' =>
                    'Ford - Chu kỳ thay dầu tiêu chuẩn trên các dòng xe thế hệ mới',

                'content' =>
                    'Ford Việt Nam cho biết với các dòng xe Ford thế hệ mới, chu kỳ thay dầu động cơ tiêu chuẩn là 10.000 km hoặc 6 tháng. Xe cụ thể vẫn cần đối chiếu lịch bảo dưỡng và hướng dẫn của dòng xe.',

                'topic' =>
                    'engine_oil',

                'keywords' => [
                    'Ford',
                    'dầu động cơ',
                    'thay dầu',
                    '10000 km',
                    '6 tháng',
                ],
            ],

            [
                'brand' =>
                    'FORD',

                'source' =>
                    'engine_oil',

                'title' =>
                    'Ford - Hệ thống theo dõi tuổi thọ dầu có thể yêu cầu thay sớm hơn',

                'content' =>
                    'Ford Việt Nam cho biết xe trang bị hệ thống theo dõi tuổi thọ dầu thông minh có thể tính toán theo điều kiện vận hành thực tế. Khi thường xuyên đi phố, kẹt xe hoặc tải nặng, hệ thống có thể báo thay dầu sớm hơn so với chu kỳ tiêu chuẩn.',

                'topic' =>
                    'oil_life_monitor',

                'keywords' => [
                    'Ford',
                    'IOLM',
                    'tuổi thọ dầu',
                    'kẹt xe',
                    'tải nặng',
                    'thay dầu sớm',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | VINFAST
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'VINFAST',

                'source' =>
                    'ev_maintenance_2026',

                'title' =>
                    'VinFast - Chu kỳ bảo dưỡng ô tô điện từ 01/01/2026',

                'content' =>
                    'Từ ngày 01/01/2026, VinFast công bố điều chỉnh mốc bảo dưỡng định kỳ cho các dòng ô tô điện thuộc phạm vi thông báo thành 15.000 km hoặc 1 năm, tùy điều kiện nào đến trước. Với xe cụ thể cần đối chiếu xem mẫu xe có thuộc phạm vi áp dụng hay không.',

                'topic' =>
                    'ev_maintenance_interval',

                'keywords' => [
                    'VinFast',
                    'xe điện',
                    '15000 km',
                    '1 năm',
                    '2026',
                ],
            ],

            [
                'brand' =>
                    'VINFAST',

                'source' =>
                    'maintenance',

                'title' =>
                    'VinFast - Bảo dưỡng phải theo đúng lịch của từng mẫu xe',

                'content' =>
                    'VinFast công bố lịch bảo dưỡng theo từng dòng xe. Khi trả lời về hạng mục hoặc thời điểm thay thế cụ thể, cần ưu tiên lịch của đúng mẫu xe thay vì áp dụng một thông số chung cho toàn bộ xe VinFast.',

                'topic' =>
                    'model_specific_maintenance',

                'keywords' => [
                    'VinFast',
                    'lịch bảo dưỡng',
                    'mẫu xe',
                    'VF 3',
                    'VF 5',
                    'VF 6',
                    'VF 7',
                    'VF 8',
                    'VF 9',
                ],
            ],

            [
                'brand' =>
                    'VINFAST',

                'source' =>
                    'maintenance',

                'title' =>
                    'VinFast - Các hệ thống xe điện cần được kiểm tra định kỳ',

                'content' =>
                    'Bảo dưỡng ô tô điện VinFast vẫn bao gồm các hạng mục cơ khí và an toàn như lốp, phanh, điều hòa, hệ thống điện 12V và các hệ thống liên quan đến truyền động điện hoặc làm mát tùy dòng xe. Hạng mục chi tiết phải theo lịch của mẫu xe.',

                'topic' =>
                    'ev_maintenance_items',

                'keywords' => [
                    'VinFast',
                    'xe điện',
                    'phanh',
                    'lốp',
                    'ắc quy 12V',
                    'làm mát',
                    'động cơ điện',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | MERCEDES-BENZ
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'MERCEDES_BENZ',

                'source' =>
                    'owner_manual',

                'title' =>
                    'Mercedes-Benz - ASSYST PLUS theo dõi thời gian và quãng đường còn lại',

                'content' =>
                    'Hệ thống ASSYST PLUS của Mercedes-Benz cung cấp thông tin về thời gian hoặc quãng đường còn lại trước kỳ dịch vụ tiếp theo. Vì vậy người dùng cần ưu tiên thông báo bảo dưỡng hiển thị trên xe và hướng dẫn của đúng mẫu xe.',

                'topic' =>
                    'assyst_plus',

                'keywords' => [
                    'Mercedes-Benz',
                    'Mercedes',
                    'ASSYST PLUS',
                    'Service A',
                    'Service B',
                    'lịch bảo dưỡng',
                ],
            ],

            [
                'brand' =>
                    'MERCEDES_BENZ',

                'source' =>
                    'owner_manual',

                'title' =>
                    'Mercedes-Benz - Điều kiện sử dụng có thể rút ngắn chu kỳ dịch vụ',

                'content' =>
                    'Mercedes-Benz nêu ASSYST PLUS có thể rút ngắn khoảng thời gian giữa các kỳ dịch vụ tùy cách sử dụng, chẳng hạn thường xuyên đi quãng đường ngắn, để động cơ chạy không tải lâu hoặc thường xuyên khởi động lạnh. Cần tuân theo thông báo thực tế của xe.',

                'topic' =>
                    'maintenance_conditions',

                'keywords' => [
                    'Mercedes-Benz',
                    'Mercedes',
                    'ASSYST PLUS',
                    'đi quãng đường ngắn',
                    'khởi động lạnh',
                    'chu kỳ bảo dưỡng',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | BMW
            |----------------------------------------------------------------------
            */

            [
                'brand' =>
                    'BMW',

                'source' =>
                    'condition_based_service',

                'title' =>
                    'BMW - Condition Based Service',

                'content' =>
                    'BMW sử dụng hệ thống Condition Based Service để xác định nhu cầu bảo dưỡng dựa trên tình trạng thực tế của xe thay vì chỉ dựa vào một khoảng thời gian cố định chung. Khi xe BMW báo hạng mục đến hạn, cần ưu tiên thông tin CBS của chính xe.',

                'topic' =>
                    'condition_based_service',

                'keywords' => [
                    'BMW',
                    'CBS',
                    'Condition Based Service',
                    'lịch bảo dưỡng',
                    'bảo dưỡng theo tình trạng',
                ],
            ],

            [
                'brand' =>
                    'BMW',

                'source' =>
                    'condition_based_service',

                'title' =>
                    'BMW - Proactive Care hỗ trợ phát hiện nhu cầu dịch vụ',

                'content' =>
                    'BMW Proactive Care sử dụng dữ liệu và các chức năng kết nối phù hợp để hỗ trợ phát hiện sớm nhu cầu dịch vụ và bảo dưỡng. Lịch thực tế cần dựa trên trạng thái xe, CBS và hướng dẫn của mẫu xe.',

                'topic' =>
                    'proactive_care',

                'keywords' => [
                    'BMW',
                    'Proactive Care',
                    'CBS',
                    'dịch vụ',
                    'bảo dưỡng',
                ],
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | GENERAL TECHNICAL KNOWLEDGE
        |--------------------------------------------------------------------------
        |
        | 15 nhóm x 3 tài liệu = 45 tài liệu.
        |
        | Đây là fallback kỹ thuật cho tất cả hãng
        | khi không có hướng dẫn OEM riêng.
        |
        */

        $generalDocuments = [

            /*
            |----------------------------------------------------------------------
            | ENGINE OIL
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'ENGINE_OIL',

                'title' =>
                    'Dầu động cơ có vai trò gì?',

                'content' =>
                    'Dầu động cơ giúp bôi trơn các bề mặt chuyển động, hỗ trợ kiểm soát ma sát, nhiệt và cặn bẩn bên trong động cơ. Chất lượng dầu suy giảm theo thời gian sử dụng nên cần kiểm tra và thay theo tiêu chuẩn của nhà sản xuất xe.',

                'keywords' => [
                    'dầu động cơ',
                    'dầu máy',
                    'bôi trơn',
                    'ma sát',
                    'nhiệt',
                ],
            ],

            [
                'source' =>
                    'ENGINE_OIL',

                'title' =>
                    'Khi nào cần thay dầu động cơ?',

                'content' =>
                    'Thời điểm thay dầu không nên áp dụng một con số chung cho mọi xe. Cần ưu tiên lịch bảo dưỡng, loại động cơ, loại dầu và hướng dẫn của nhà sản xuất. Điều kiện vận hành nặng có thể làm dầu xuống cấp nhanh hơn.',

                'keywords' => [
                    'thay dầu',
                    'dầu máy',
                    'bao lâu thay dầu',
                    'chu kỳ thay dầu',
                ],
            ],

            [
                'source' =>
                    'ENGINE_OIL',

                'title' =>
                    'Không nên tự chọn độ nhớt dầu chỉ dựa vào hãng xe',

                'content' =>
                    'Độ nhớt và tiêu chuẩn dầu cần phù hợp với đúng động cơ và hướng dẫn của nhà sản xuất. Hai xe cùng hãng có thể yêu cầu loại dầu khác nhau, vì vậy chatbot không được suy đoán độ nhớt khi chưa có tài liệu của dòng xe.',

                'keywords' => [
                    'độ nhớt',
                    '5W-30',
                    '0W-20',
                    'chọn dầu',
                    'tiêu chuẩn dầu',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | TIRES
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'TIRES',

                'title' =>
                    'Kiểm tra áp suất lốp',

                'content' =>
                    'Áp suất lốp cần được kiểm tra định kỳ và đặt theo thông số của nhà sản xuất xe. Áp suất không phù hợp có thể ảnh hưởng đến độ bám, độ mòn lốp, khả năng điều khiển và mức tiêu thụ năng lượng.',

                'keywords' => [
                    'lốp',
                    'áp suất lốp',
                    'TPMS',
                    'lốp non',
                    'lốp căng',
                ],
            ],

            [
                'source' =>
                    'TIRES',

                'title' =>
                    'Dấu hiệu lốp cần được kiểm tra',

                'content' =>
                    'Lốp cần được kiểm tra khi xuất hiện mòn không đều, nứt, phồng, thủng, rung bất thường hoặc cảnh báo áp suất. Không nên chỉ dựa vào số kilomet để quyết định tình trạng an toàn của lốp.',

                'keywords' => [
                    'lốp mòn',
                    'lốp nứt',
                    'lốp phồng',
                    'rung',
                    'mòn không đều',
                ],
            ],

            [
                'source' =>
                    'TIRES',

                'title' =>
                    'Đảo lốp, cân bằng và căn chỉnh bánh xe',

                'content' =>
                    'Đảo lốp giúp hỗ trợ phân bố độ mòn phù hợp. Cân bằng bánh xe và căn chỉnh góc đặt bánh xe là các công việc khác nhau; xe rung hoặc mòn lốp bất thường có thể cần kiểm tra những hạng mục này thay vì chỉ thay lốp.',

                'keywords' => [
                    'đảo lốp',
                    'cân bằng bánh xe',
                    'cân chỉnh',
                    'góc đặt bánh xe',
                    'rung vô lăng',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | BRAKES
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'BRAKES',

                'title' =>
                    'Phanh phát tiếng kêu cần kiểm tra gì?',

                'content' =>
                    'Tiếng kêu khi phanh có thể liên quan đến nhiều yếu tố như tình trạng má phanh, đĩa phanh, bề mặt tiếp xúc, bụi bẩn hoặc các bộ phận liên quan. Không nên chẩn đoán chắc chắn chỉ dựa trên tiếng kêu; cần kiểm tra trực tiếp hệ thống phanh.',

                'keywords' => [
                    'phanh kêu',
                    'tiếng kêu phanh',
                    'má phanh',
                    'đĩa phanh',
                    'phanh',
                ],
            ],

            [
                'source' =>
                    'BRAKES',

                'title' =>
                    'Xe rung khi phanh',

                'content' =>
                    'Rung xuất hiện khi đạp phanh có thể liên quan đến các bộ phận của hệ thống phanh hoặc bánh xe. Vì đây là hệ thống an toàn, xe nên được kiểm tra trực tiếp để xác định nguyên nhân thay vì tiếp tục sử dụng dựa trên phỏng đoán.',

                'keywords' => [
                    'rung khi phanh',
                    'phanh rung',
                    'vô lăng rung',
                    'đĩa phanh',
                ],
            ],

            [
                'source' =>
                    'BRAKES',

                'title' =>
                    'Dấu hiệu phanh cần kiểm tra sớm',

                'content' =>
                    'Các dấu hiệu như giảm hiệu quả phanh, bàn đạp bất thường, xe lệch khi phanh, rung hoặc tiếng kêu kéo dài cần được kiểm tra sớm. Nếu cảm thấy mất lực phanh hoặc khả năng dừng xe không an toàn, không nên tiếp tục vận hành xe.',

                'keywords' => [
                    'mất phanh',
                    'phanh yếu',
                    'bàn đạp phanh',
                    'xe lệch khi phanh',
                    'phanh không ăn',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | BATTERY 12V
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'BATTERY_12V',

                'title' =>
                    'Dấu hiệu ắc quy 12V yếu',

                'content' =>
                    'Ắc quy 12V yếu có thể biểu hiện qua việc khởi động chậm, hệ thống điện hoạt động không ổn định hoặc các dấu hiệu điện áp thấp. Cần kiểm tra tình trạng và điện áp ắc quy trước khi kết luận phải thay mới.',

                'keywords' => [
                    'ắc quy yếu',
                    'khó khởi động',
                    'đề chậm',
                    'ắc quy 12V',
                ],
            ],

            [
                'source' =>
                    'BATTERY_12V',

                'title' =>
                    'Kiểm tra cọc và đầu nối ắc quy',

                'content' =>
                    'Cọc ắc quy và đầu nối cần sạch, chắc chắn và không bị ăn mòn nghiêm trọng. Tiếp xúc điện kém có thể gây triệu chứng giống ắc quy yếu, vì vậy cần kiểm tra trước khi quyết định thay ắc quy.',

                'keywords' => [
                    'cọc ắc quy',
                    'ăn mòn',
                    'đầu nối',
                    'ắc quy',
                ],
            ],

            [
                'source' =>
                    'BATTERY_12V',

                'title' =>
                    'Tuổi thọ ắc quy không giống nhau trên mọi xe',

                'content' =>
                    'Tuổi thọ thực tế của ắc quy phụ thuộc môi trường, tần suất sử dụng, nhiệt độ và hệ thống điện của xe. Không nên kết luận phải thay chỉ dựa vào số năm; kiểm tra tình trạng thực tế là cần thiết.',

                'keywords' => [
                    'tuổi thọ ắc quy',
                    'thay ắc quy',
                    'kiểm tra ắc quy',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | SPARK PLUGS
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'SPARK_PLUGS',

                'title' =>
                    'Chức năng của bugi',

                'content' =>
                    'Bugi tạo tia lửa để bắt đầu quá trình cháy trong động cơ xăng. Bugi không áp dụng theo cách tương tự cho động cơ diesel hoặc xe điện, vì vậy cần xác định loại hệ truyền động trước khi tư vấn.',

                'keywords' => [
                    'bugi',
                    'tia lửa',
                    'động cơ xăng',
                ],
            ],

            [
                'source' =>
                    'SPARK_PLUGS',

                'title' =>
                    'Dấu hiệu bugi có thể cần kiểm tra',

                'content' =>
                    'Bugi mòn hoặc hoạt động kém có thể liên quan đến khó khởi động, động cơ hoạt động không đều, giảm công suất hoặc tiêu hao nhiên liệu tăng. Những triệu chứng này cũng có thể do nguyên nhân khác nên cần chẩn đoán.',

                'keywords' => [
                    'bugi mòn',
                    'khó nổ',
                    'máy rung',
                    'hao nhiên liệu',
                    'mất công suất',
                ],
            ],

            [
                'source' =>
                    'SPARK_PLUGS',

                'title' =>
                    'Chu kỳ thay bugi phải theo đúng loại bugi và động cơ',

                'content' =>
                    'Không có một chu kỳ thay bugi chung cho mọi xe. Tuổi thọ phụ thuộc vật liệu bugi, động cơ và lịch bảo dưỡng của nhà sản xuất. Cần dùng thông tin model-specific khi đưa ra mốc thay chính xác.',

                'keywords' => [
                    'thay bugi',
                    'chu kỳ bugi',
                    'bao lâu thay bugi',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | COOLING SYSTEM
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'COOLING_SYSTEM',

                'title' =>
                    'Vai trò của hệ thống làm mát động cơ',

                'content' =>
                    'Hệ thống làm mát giúp kiểm soát nhiệt độ vận hành. Các thành phần có thể gồm dung dịch làm mát, két nước, bơm nước, thermostat, quạt và đường ống tùy thiết kế xe.',

                'keywords' => [
                    'hệ thống làm mát',
                    'két nước',
                    'bơm nước',
                    'thermostat',
                    'nước làm mát',
                ],
            ],

            [
                'source' =>
                    'COOLING_SYSTEM',

                'title' =>
                    'Dấu hiệu rò rỉ hệ thống làm mát',

                'content' =>
                    'Mức dung dịch giảm bất thường, dấu vết chất lỏng, mùi bất thường hoặc nhiệt độ động cơ tăng có thể là lý do để kiểm tra hệ thống làm mát. Không nên chỉ châm thêm dung dịch nhiều lần mà bỏ qua nguyên nhân rò rỉ.',

                'keywords' => [
                    'rò nước làm mát',
                    'thiếu nước làm mát',
                    'rò rỉ',
                    'nhiệt độ cao',
                ],
            ],

            [
                'source' =>
                    'COOLING_SYSTEM',

                'title' =>
                    'Không mở nắp hệ thống làm mát khi đang quá nóng',

                'content' =>
                    'Khi động cơ đang quá nóng, hệ thống làm mát có thể có áp suất và nhiệt độ cao. Không nên mở nắp két nước hoặc bình áp suất khi chưa an toàn vì có nguy cơ gây bỏng.',

                'keywords' => [
                    'mở nắp két nước',
                    'nóng máy',
                    'bỏng',
                    'nước làm mát nóng',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | OVERHEATING
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'OVERHEATING',

                'title' =>
                    'Dấu hiệu động cơ quá nhiệt',

                'content' =>
                    'Cảnh báo nhiệt độ cao, kim nhiệt độ tăng bất thường, hơi nước hoặc dấu hiệu quá nóng từ khoang động cơ là những tình huống cần xử lý nghiêm túc. Tiếp tục chạy khi động cơ quá nhiệt có thể gây thêm hư hỏng.',

                'keywords' => [
                    'quá nhiệt',
                    'nóng máy',
                    'kim nhiệt',
                    'hơi nước',
                ],
            ],

            [
                'source' =>
                    'OVERHEATING',

                'title' =>
                    'Xử lý ban đầu khi xe có dấu hiệu quá nhiệt',

                'content' =>
                    'Khi xe có dấu hiệu quá nhiệt nghiêm trọng, cần ưu tiên đưa xe đến vị trí an toàn, dừng vận hành và để hệ thống nguội trước khi kiểm tra. Không tiếp tục lái chỉ để cố tới điểm đến nếu nhiệt độ vẫn bất thường.',

                'keywords' => [
                    'xử lý quá nhiệt',
                    'xe nóng máy',
                    'dừng xe',
                    'nhiệt độ cao',
                ],
            ],

            [
                'source' =>
                    'OVERHEATING',

                'title' =>
                    'Nguyên nhân quá nhiệt cần được kiểm tra thực tế',

                'content' =>
                    'Quá nhiệt có thể liên quan đến nhiều thành phần như mức dung dịch làm mát, rò rỉ, thermostat, bơm nước, quạt hoặc két nước. Chatbot chỉ có thể nêu khả năng cần kiểm tra, không được kết luận nguyên nhân chắc chắn.',

                'keywords' => [
                    'nguyên nhân quá nhiệt',
                    'thermostat',
                    'bơm nước',
                    'quạt két nước',
                    'rò rỉ',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | BELTS / HOSES
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'BELTS_HOSES',

                'title' =>
                    'Dấu hiệu dây đai phụ cần kiểm tra',

                'content' =>
                    'Dây đai có dấu hiệu nứt, sờn, hư hỏng hoặc phát tiếng bất thường cần được kiểm tra cùng hệ thống tăng đai và puly. Không nên chỉ thay dây nếu nguyên nhân đến từ bộ phận liên quan.',

                'keywords' => [
                    'dây curoa',
                    'dây đai',
                    'nứt dây đai',
                    'tiếng kêu dây đai',
                    'puly',
                ],
            ],

            [
                'source' =>
                    'BELTS_HOSES',

                'title' =>
                    'Bộ tăng đai và puly cũng ảnh hưởng đến dây đai',

                'content' =>
                    'Tiếng kêu hoặc mòn dây đai không nhất thiết chỉ do bản thân dây. Bộ tăng đai, puly hoặc sai lệch hệ thống truyền động phụ cũng có thể cần được kiểm tra.',

                'keywords' => [
                    'tăng đai',
                    'puly',
                    'dây đai kêu',
                    'dây curoa kêu',
                ],
            ],

            [
                'source' =>
                    'BELTS_HOSES',

                'title' =>
                    'Ống hệ thống làm mát cần kiểm tra rò rỉ và hư hỏng',

                'content' =>
                    'Các ống trong hệ thống làm mát cần được kiểm tra về rò rỉ, nứt, biến dạng và liên kết. Hư hỏng đường ống có thể làm thất thoát dung dịch và dẫn tới tình trạng nhiệt độ bất thường.',

                'keywords' => [
                    'ống nước',
                    'ống làm mát',
                    'rò rỉ',
                    'nứt ống',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | AIR CONDITIONING
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'AIR_CONDITIONING',

                'title' =>
                    'Điều hòa ô tô không mát cần kiểm tra nhiều thành phần',

                'content' =>
                    'Điều hòa không mát có thể liên quan đến môi chất lạnh, máy nén, dàn nóng, dàn lạnh, luồng gió hoặc các thành phần điều khiển. Cần kiểm tra hệ thống thay vì mặc định rằng chỉ cần nạp thêm gas.',

                'keywords' => [
                    'điều hòa không mát',
                    'điều hòa yếu',
                    'máy nén',
                    'gas điều hòa',
                ],
            ],

            [
                'source' =>
                    'AIR_CONDITIONING',

                'title' =>
                    'Máy nén là một thành phần quan trọng của điều hòa ô tô',

                'content' =>
                    'Máy nén là một thành phần của chu trình điều hòa. Khi hệ thống có tiếng bất thường hoặc không tạo lạnh, cần kiểm tra toàn hệ thống trước khi kết luận máy nén hỏng.',

                'keywords' => [
                    'máy nén điều hòa',
                    'lốc điều hòa',
                    'điều hòa kêu',
                ],
            ],

            [
                'source' =>
                    'AIR_CONDITIONING',

                'title' =>
                    'Không nên tự xả hoặc nạp môi chất lạnh tùy ý',

                'content' =>
                    'Hệ thống điều hòa sử dụng môi chất và thiết bị chuyên dụng. Việc xác định lượng môi chất, rò rỉ và quy trình nạp cần theo tiêu chuẩn kỹ thuật của xe và thiết bị dịch vụ phù hợp.',

                'keywords' => [
                    'gas điều hòa',
                    'nạp gas',
                    'môi chất lạnh',
                    'rò gas',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | FILTERS
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'FILTERS',

                'title' =>
                    'Lọc gió động cơ bẩn ảnh hưởng đến luồng khí nạp',

                'content' =>
                    'Lọc gió động cơ giúp hạn chế bụi bẩn đi vào hệ thống nạp. Khi lọc quá bẩn, luồng khí có thể bị hạn chế. Thời điểm thay phụ thuộc thiết kế xe và điều kiện sử dụng.',

                'keywords' => [
                    'lọc gió động cơ',
                    'lọc gió bẩn',
                    'khí nạp',
                ],
            ],

            [
                'source' =>
                    'FILTERS',

                'title' =>
                    'Lọc gió điều hòa ảnh hưởng đến luồng gió trong cabin',

                'content' =>
                    'Lọc gió điều hòa giúp lọc không khí đi vào cabin. Lọc bẩn có thể làm giảm luồng gió và cần được kiểm tra hoặc thay theo lịch phù hợp của xe.',

                'keywords' => [
                    'lọc gió điều hòa',
                    'lọc cabin',
                    'gió điều hòa yếu',
                ],
            ],

            [
                'source' =>
                    'FILTERS',

                'title' =>
                    'Lọc dầu cần được thay theo lịch phù hợp',

                'content' =>
                    'Lọc dầu giữ lại cặn bẩn trong hệ thống bôi trơn. Chu kỳ thay lọc cần theo lịch bảo dưỡng của động cơ và thường được phối hợp với công việc thay dầu theo yêu cầu của nhà sản xuất.',

                'keywords' => [
                    'lọc dầu',
                    'lọc nhớt',
                    'thay lọc dầu',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | TRANSMISSION
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'TRANSMISSION',

                'title' =>
                    'Dầu hộp số phải theo đúng tiêu chuẩn của hộp số',

                'content' =>
                    'Dầu hộp số không nên lựa chọn chỉ dựa vào hãng xe. Các loại hộp số khác nhau có thể sử dụng tiêu chuẩn dầu khác nhau. Khi chưa xác định đúng model và hộp số, chatbot không được tự đề xuất loại dầu cụ thể.',

                'keywords' => [
                    'dầu hộp số',
                    'ATF',
                    'CVT',
                    'DCT',
                    'hộp số',
                ],
            ],

            [
                'source' =>
                    'TRANSMISSION',

                'title' =>
                    'Chu kỳ thay dầu hộp số cần ưu tiên nhà sản xuất',

                'content' =>
                    'Chu kỳ bảo dưỡng dầu hộp số phụ thuộc thiết kế hộp số và hướng dẫn của nhà sản xuất. Không nên áp dụng một mốc kilomet chung cho tất cả xe.',

                'keywords' => [
                    'thay dầu hộp số',
                    'bao lâu thay dầu hộp số',
                    'chu kỳ hộp số',
                ],
            ],

            [
                'source' =>
                    'TRANSMISSION',

                'title' =>
                    'Sang số bất thường cần được kiểm tra',

                'content' =>
                    'Hiện tượng giật, trễ hoặc sang số bất thường có thể có nhiều nguyên nhân. Cần kiểm tra tình trạng dầu, dữ liệu chẩn đoán và hệ thống hộp số phù hợp thay vì kết luận hỏng hộp số chỉ từ triệu chứng.',

                'keywords' => [
                    'hộp số giật',
                    'sang số giật',
                    'trễ số',
                    'hộp số bất thường',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | SUSPENSION / STEERING
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'SUSPENSION_STEERING',

                'title' =>
                    'Dấu hiệu giảm xóc hoặc hệ thống treo cần kiểm tra',

                'content' =>
                    'Xe nảy nhiều, nghiêng bất thường, chúi đầu khi phanh, khó ổn định hoặc mòn lốp không đều có thể là lý do cần kiểm tra giảm xóc và hệ thống treo.',

                'keywords' => [
                    'giảm xóc',
                    'hệ thống treo',
                    'xe nảy',
                    'xe nghiêng',
                    'chúi đầu',
                ],
            ],

            [
                'source' =>
                    'SUSPENSION_STEERING',

                'title' =>
                    'Xe rung không đồng nghĩa chắc chắn hỏng giảm xóc',

                'content' =>
                    'Rung xe có thể liên quan đến lốp, bánh xe, hệ thống treo, hệ thống lái hoặc các bộ phận khác. Cần xác định rung xảy ra ở tốc độ nào và trong điều kiện nào trước khi chẩn đoán.',

                'keywords' => [
                    'xe rung',
                    'rung vô lăng',
                    'rung khi chạy',
                    'hệ thống treo',
                ],
            ],

            [
                'source' =>
                    'SUSPENSION_STEERING',

                'title' =>
                    'Mòn lốp không đều có thể liên quan đến hệ thống treo hoặc góc đặt bánh xe',

                'content' =>
                    'Mòn lốp không đều có thể là dấu hiệu cần kiểm tra áp suất, góc đặt bánh xe, hệ thống lái hoặc hệ thống treo. Không nên chỉ thay lốp mà bỏ qua nguyên nhân cơ học.',

                'keywords' => [
                    'mòn lốp không đều',
                    'góc đặt bánh xe',
                    'hệ thống treo',
                    'hệ thống lái',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | WARNING LIGHTS
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'WARNING_LIGHTS',

                'title' =>
                    'Đèn Check Engine sáng cần được chẩn đoán',

                'content' =>
                    'Đèn Check Engine có thể được kích hoạt bởi nhiều nguyên nhân liên quan đến động cơ hoặc hệ thống kiểm soát khí thải. Cần đọc mã chẩn đoán và kiểm tra thực tế trước khi xác định nguyên nhân.',

                'keywords' => [
                    'check engine',
                    'đèn động cơ',
                    'đèn báo lỗi',
                    'mã lỗi',
                ],
            ],

            [
                'source' =>
                    'WARNING_LIGHTS',

                'title' =>
                    'Không nên chỉ xóa mã lỗi để tắt đèn cảnh báo',

                'content' =>
                    'Xóa mã lỗi có thể làm mất dữ liệu hữu ích cho quá trình chẩn đoán mà không giải quyết nguyên nhân. Nếu đèn cảnh báo xuất hiện trở lại, hệ thống cần được kiểm tra.',

                'keywords' => [
                    'xóa mã lỗi',
                    'xóa check engine',
                    'đèn cảnh báo',
                    'mã lỗi',
                ],
            ],

            [
                'source' =>
                    'WARNING_LIGHTS',

                'title' =>
                    'Đèn cảnh báo đi kèm triệu chứng nghiêm trọng cần ưu tiên an toàn',

                'content' =>
                    'Nếu đèn cảnh báo đi kèm mất công suất nghiêm trọng, tiếng động bất thường, quá nhiệt hoặc dấu hiệu mất an toàn, người lái nên ưu tiên dừng xe ở nơi an toàn và yêu cầu kiểm tra kỹ thuật.',

                'keywords' => [
                    'đèn cảnh báo',
                    'mất công suất',
                    'quá nhiệt',
                    'tiếng động bất thường',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | FLOOD DAMAGE
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'FLOOD_DAMAGE',

                'title' =>
                    'Xe bị ngập nước không nên cố khởi động lại',

                'content' =>
                    'Sau khi xe bị ngập nước, không nên cố khởi động động cơ trước khi xe được kiểm tra phù hợp. Việc khởi động có thể làm tăng mức độ hư hỏng nếu nước đã xâm nhập vào các hệ thống quan trọng.',

                'keywords' => [
                    'xe ngập nước',
                    'xe bị ngập',
                    'khởi động sau ngập',
                    'nước vào xe',
                ],
            ],

            [
                'source' =>
                    'FLOOD_DAMAGE',

                'title' =>
                    'Xe sau ngập cần được kiểm tra nhiều hệ thống',

                'content' =>
                    'Xe sau ngập có thể cần kiểm tra hệ thống điện, chất lỏng, nội thất, phanh và các khu vực có khả năng bị nước xâm nhập. Mức độ kiểm tra phụ thuộc độ sâu và thời gian xe tiếp xúc với nước.',

                'keywords' => [
                    'kiểm tra xe ngập',
                    'hệ thống điện',
                    'nước vào xe',
                    'sau ngập',
                ],
            ],

            [
                'source' =>
                    'FLOOD_DAMAGE',

                'title' =>
                    'Không đánh giá xe ngập chỉ bằng ngoại quan',

                'content' =>
                    'Một chiếc xe có thể không biểu hiện hư hỏng rõ ràng ngay sau khi nước rút. Nước và độ ẩm có thể ảnh hưởng đến điện, kết nối và các bộ phận khác, vì vậy cần kiểm tra chuyên môn trước khi tiếp tục sử dụng.',

                'keywords' => [
                    'xe ngập',
                    'ẩm điện',
                    'hư hỏng do nước',
                    'kiểm tra sau ngập',
                ],
            ],


            /*
            |----------------------------------------------------------------------
            | ELECTRIC VEHICLE
            |----------------------------------------------------------------------
            */

            [
                'source' =>
                    'ELECTRIC_VEHICLE',

                'title' =>
                    'Xe điện vẫn cần bảo dưỡng định kỳ',

                'content' =>
                    'Xe điện có ít hạng mục liên quan đến động cơ đốt trong hơn nhưng vẫn cần kiểm tra các hệ thống như lốp, phanh, hệ thống điều hòa, ắc quy 12V và các hệ thống nhiệt hoặc làm mát tùy thiết kế.',

                'keywords' => [
                    'xe điện',
                    'bảo dưỡng xe điện',
                    'EV',
                    'lốp',
                    'phanh',
                    'ắc quy 12V',
                ],
            ],

            [
                'source' =>
                    'ELECTRIC_VEHICLE',

                'title' =>
                    'Phanh tái sinh không loại bỏ nhu cầu kiểm tra phanh cơ khí',

                'content' =>
                    'Xe điện có thể sử dụng phanh tái sinh để giảm mức sử dụng phanh ma sát trong một số điều kiện, nhưng hệ thống phanh cơ khí vẫn là hệ thống an toàn cần được kiểm tra và bảo dưỡng.',

                'keywords' => [
                    'phanh tái sinh',
                    'xe điện',
                    'phanh cơ khí',
                    'EV',
                ],
            ],

            [
                'source' =>
                    'ELECTRIC_VEHICLE',

                'title' =>
                    'Không tự thao tác hệ thống điện cao áp của xe điện',

                'content' =>
                    'Pin cao áp, cáp điện cao áp và các thành phần điện áp cao của xe điện cần quy trình và thiết bị an toàn chuyên dụng. Người dùng không nên tự tháo hoặc sửa chữa hệ thống cao áp.',

                'keywords' => [
                    'pin cao áp',
                    'điện cao áp',
                    'cáp cao áp',
                    'xe điện',
                    'an toàn EV',
                ],
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | SAVE MANAGED STATIC DOCUMENTS
        |--------------------------------------------------------------------------
        */

        $managedStaticIds = [];

        foreach (
            $internalDocuments
            as $document
        ) {
            $saved =
                $storeDocument(
                    [
                        'source_type' =>
                            $document[
                                'source_type'
                            ],

                        'title' =>
                            $document[
                                'title'
                            ],
                    ],
                    [
                        'source_type' =>
                            $document[
                                'source_type'
                            ],

                        'source_id' =>
                            null,

                        'title' =>
                            $document[
                                'title'
                            ],

                        'content' =>
                            $document[
                                'content'
                            ],

                        'metadata' =>
                            $document[
                                'metadata'
                            ],

                        'is_active' =>
                            true,
                    ]
                );

            $managedStaticIds[] =
                $saved->id;
        }


        foreach (
            $brandDocuments
            as $document
        ) {
            $metadata =
                $brandSourceMetadata(
                    $document[
                        'brand'
                    ],
                    $document[
                        'source'
                    ],
                    [
                        'topic' =>
                            $document[
                                'topic'
                            ],

                        'model' =>
                            $document[
                                'model'
                            ]
                            ?? null,

                        'keywords' =>
                            $document[
                                'keywords'
                            ]
                            ?? [],
                    ]
                );

            $saved =
                $storeDocument(
                    [
                        'source_type' =>
                            'OEM_GUIDE',

                        'title' =>
                            $document[
                                'title'
                            ],
                    ],
                    [
                        'source_type' =>
                            'OEM_GUIDE',

                        'source_id' =>
                            null,

                        'title' =>
                            $document[
                                'title'
                            ],

                        'content' =>
                            $document[
                                'content'
                            ],

                        'metadata' =>
                            $metadata,

                        'is_active' =>
                            true,
                    ]
                );

            $managedStaticIds[] =
                $saved->id;
        }


        foreach (
            $generalDocuments
            as $document
        ) {
            $sourceConfig =
                config(
                    'knowledge_sources.general.'
                    . $document[
                        'source'
                    ]
                );

            $metadata =
                $generalSourceMetadata(
                    $document[
                        'source'
                    ],
                    [
                        'topic' =>
                            $sourceConfig[
                                'topic'
                            ]
                            ?? null,

                        'keywords' =>
                            $document[
                                'keywords'
                            ]
                            ?? [],
                    ]
                );

            $saved =
                $storeDocument(
                    [
                        'source_type' =>
                            'GENERAL_GUIDE',

                        'title' =>
                            $document[
                                'title'
                            ],
                    ],
                    [
                        'source_type' =>
                            'GENERAL_GUIDE',

                        'source_id' =>
                            null,

                        'title' =>
                            $document[
                                'title'
                            ],

                        'content' =>
                            $document[
                                'content'
                            ],

                        'metadata' =>
                            $metadata,

                        'is_active' =>
                            true,
                    ]
                );

            $managedStaticIds[] =
                $saved->id;
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE OLD STATIC SEEDED DOCUMENTS
        |--------------------------------------------------------------------------
        |
        | Xóa các tài liệu cũ thuộc nhóm Seeder quản lý
        | nhưng không còn nằm trong bộ Knowledge Base mới.
        |
        | SERVICE được xử lý riêng bên dưới.
        |
        */

        $managedStaticTypes = [
            'BUSINESS_INFO',
            'FAQ',
            'AI_POLICY',
            'OEM_GUIDE',
            'GENERAL_GUIDE',
        ];

        KnowledgeDocument::query()
            ->whereIn(
                'source_type',
                $managedStaticTypes
            )
            ->whereNotIn(
                'id',
                $managedStaticIds
            )
            ->delete();


        /*
        |--------------------------------------------------------------------------
        | AUTOCARE SERVICE CATALOG
        |--------------------------------------------------------------------------
        */

        $services =
            Service::query()
                ->with(
                    'category'
                )
                ->get();

        $currentServiceIds = [];


        foreach (
            $services
            as $service
        ) {
            $categoryName =
                $service
                    ->category
                    ?->name
                ?? 'Chưa phân loại';


            $contentParts = [
                "Tên dịch vụ: {$service->name}.",
                "Danh mục: {$categoryName}.",
            ];


            if (
                filled(
                    $service->description
                )
            ) {
                $contentParts[] =
                    'Mô tả: '
                    . $service->description
                    . '.';
            }


            $contentParts[] =
                'Giá tham khảo tại AutoCare: '
                . number_format(
                    (float) $service->base_price,
                    0,
                    ',',
                    '.'
                )
                . ' đồng.';


            if (
                $service
                    ->estimated_duration_minutes
            ) {
                $contentParts[] =
                    'Thời gian thực hiện dự kiến: '
                    . $service
                        ->estimated_duration_minutes
                    . ' phút.';
            }


            if (
                $service
                    ->mileage_interval
            ) {
                $contentParts[] =
                    'Chu kỳ tham khảo theo kilomet: '
                    . number_format(
                        (float)
                        $service
                            ->mileage_interval,
                        0,
                        ',',
                        '.'
                    )
                    . ' km.';
            }


            if (
                $service
                    ->month_interval
            ) {
                $contentParts[] =
                    'Chu kỳ tham khảo theo thời gian: '
                    . $service
                        ->month_interval
                    . ' tháng.';
            }


            $metadata = [
                'topic' =>
                    'autocare_service',

                'scope' =>
                    'AUTOCARE_SERVICE',

                'brand' =>
                    null,

                'model' =>
                    null,

                'service_id' =>
                    $service->id,

                'category' =>
                    $categoryName,

                'base_price' =>
                    $service
                        ->base_price,

                'mileage_interval' =>
                    $service
                        ->mileage_interval,

                'month_interval' =>
                    $service
                        ->month_interval,

                'estimated_duration_minutes' =>
                    $service
                        ->estimated_duration_minutes,

                'source_name' =>
                    'AutoCare Long Biên',

                'source_title' =>
                    'Danh mục dịch vụ AutoCare - '
                    . $service->name,

                'source_url' =>
                    $appUrl
                    . '/services/'
                    . $service->id,

                'reference_type' =>
                    'AUTOCARE_INTERNAL',

                'accessed_at' =>
                    $accessedAt,

                'source_verified' =>
                    true,

                'keywords' => [
                    $service->name,
                    $categoryName,
                    'dịch vụ AutoCare',
                    'giá dịch vụ',
                ],
            ];


            $saved =
                $storeDocument(
                    [
                        'source_type' =>
                            'SERVICE',

                        'source_id' =>
                            $service->id,
                    ],
                    [
                        'source_type' =>
                            'SERVICE',

                        'source_id' =>
                            $service->id,

                        'title' =>
                            $service->name,

                        'content' =>
                            implode(
                                ' ',
                                $contentParts
                            ),

                        'metadata' =>
                            $metadata,

                        'is_active' =>
                            true,
                    ]
                );


            $currentServiceIds[] =
                $saved->id;
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE STALE SERVICE DOCUMENTS
        |--------------------------------------------------------------------------
        */

        $serviceQuery =
            KnowledgeDocument::query()
                ->where(
                    'source_type',
                    'SERVICE'
                );


        if (
            empty(
                $currentServiceIds
            )
        ) {
            $serviceQuery
                ->delete();
        } else {
            $serviceQuery
                ->whereNotIn(
                    'id',
                    $currentServiceIds
                )
                ->delete();
        }
    }
}