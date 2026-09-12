<?php

namespace Database\Seeders;

use App\Models\KnowledgeDocument;
use App\Models\Service;
use Illuminate\Database\Seeder;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Thông tin chung về AutoCare
        |--------------------------------------------------------------------------
        */

        $documents = [
            [
                'title' => 'Giới thiệu AutoCare Long Biên',
                'content' => implode(' ', [
                    'AutoCare Long Biên là hệ thống thông tin quản lý',
                    'và hỗ trợ bảo dưỡng ô tô tại khu vực Long Biên, Hà Nội.',
                    'Hệ thống hỗ trợ khách hàng quản lý phương tiện,',
                    'đặt lịch bảo dưỡng, theo dõi lịch sử bảo dưỡng,',
                    'theo dõi hóa đơn và nhận hỗ trợ tư vấn',
                    'liên quan đến chăm sóc và bảo dưỡng ô tô.',
                ]),
                'source_type' => 'BUSINESS_INFO',
                'source_id' => null,
                'metadata' => [
                    'topic' => 'about',
                ],
            ],

            [
                'title' => 'Quy trình đặt lịch bảo dưỡng',
                'content' => implode(' ', [
                    'Khách hàng đăng nhập vào AutoCare Long Biên,',
                    'chọn phương tiện của mình, chọn một hoặc nhiều dịch vụ,',
                    'chọn ngày và giờ mong muốn rồi gửi yêu cầu đặt lịch.',
                    'Lịch hẹn ban đầu có trạng thái chờ xác nhận.',
                    'Sau khi nhân viên xác nhận, xe có thể được tiếp nhận',
                    'và tạo phiếu bảo dưỡng.',
                ]),
                'source_type' => 'FAQ',
                'source_id' => null,
                'metadata' => [
                    'topic' => 'appointment',
                ],
            ],

            [
                'title' => 'Quy trình bảo dưỡng tại AutoCare',
                'content' => implode(' ', [
                    'Sau khi lịch hẹn được xác nhận, nhân viên có thể',
                    'tiếp nhận xe và tạo phiếu bảo dưỡng.',
                    'Phiếu được phân công cho kỹ thuật viên.',
                    'Kỹ thuật viên thực hiện từng hạng mục, cập nhật tiến độ',
                    'và ghi chú kỹ thuật.',
                    'Khi toàn bộ hạng mục hoàn thành, phiếu bảo dưỡng',
                    'được hoàn tất và nhân viên có thể lập hóa đơn.',
                ]),
                'source_type' => 'FAQ',
                'source_id' => null,
                'metadata' => [
                    'topic' => 'maintenance_process',
                ],
            ],

            [
                'title' => 'Lịch sử bảo dưỡng',
                'content' => implode(' ', [
                    'Khách hàng đã đăng nhập có thể xem lịch sử bảo dưỡng',
                    'của các phương tiện thuộc tài khoản của mình.',
                    'Thông tin có thể bao gồm phiếu bảo dưỡng,',
                    'các dịch vụ đã thực hiện, ODO khi tiếp nhận,',
                    'kỹ thuật viên, thời điểm hoàn thành và tổng chi phí.',
                ]),
                'source_type' => 'FAQ',
                'source_id' => null,
                'metadata' => [
                    'topic' => 'maintenance_history',
                ],
            ],

            [
                'title' => 'Hóa đơn và thanh toán',
                'content' => implode(' ', [
                    'Sau khi phiếu bảo dưỡng hoàn thành,',
                    'nhân viên có thể lập hóa đơn.',
                    'Hóa đơn lưu các hạng mục dịch vụ và phụ tùng',
                    'tại thời điểm lập hóa đơn.',
                    'Các phương thức thanh toán của hệ thống gồm',
                    'tiền mặt, chuyển khoản ngân hàng và thẻ.',
                ]),
                'source_type' => 'FAQ',
                'source_id' => null,
                'metadata' => [
                    'topic' => 'invoice',
                ],
            ],

            [
                'title' => 'Quản lý phương tiện',
                'content' => implode(' ', [
                    'Khách hàng có thể thêm và quản lý phương tiện',
                    'trong tài khoản AutoCare.',
                    'Thông tin phương tiện có thể gồm hãng xe, dòng xe,',
                    'biển số, VIN, năm sản xuất, màu xe, loại nhiên liệu',
                    'và số kilomet hiện tại.',
                    'ODO nên được cập nhật chính xác để hỗ trợ',
                    'theo dõi và gợi ý chu kỳ bảo dưỡng.',
                ]),
                'source_type' => 'FAQ',
                'source_id' => null,
                'metadata' => [
                    'topic' => 'vehicle',
                ],
            ],

            [
                'title' => 'Nguyên tắc gợi ý bảo dưỡng',
                'content' => implode(' ', [
                    'Gợi ý bảo dưỡng có thể dựa trên số kilomet hiện tại,',
                    'chu kỳ kilomet của dịch vụ, chu kỳ thời gian',
                    'và lịch sử bảo dưỡng đã hoàn thành.',
                    'Gợi ý chỉ mang tính hỗ trợ.',
                    'Tình trạng thực tế của xe vẫn cần được kiểm tra',
                    'bởi người có chuyên môn khi cần thiết.',
                ]),
                'source_type' => 'FAQ',
                'source_id' => null,
                'metadata' => [
                    'topic' => 'recommendation',
                ],
            ],

            [
                'title' => 'Phạm vi tư vấn của AutoCare AI',
                'content' => implode(' ', [
                    'AutoCare AI được thiết kế để hỗ trợ người dùng',
                    'tra cứu thông tin dịch vụ, quy trình sử dụng hệ thống,',
                    'thông tin bảo dưỡng và dữ liệu AutoCare',
                    'mà người dùng có quyền truy cập.',
                    'Chatbot không thay thế việc kiểm tra trực tiếp',
                    'của kỹ thuật viên đối với các sự cố nghiêm trọng',
                    'hoặc tình trạng xe không thể xác định từ dữ liệu.',
                ]),
                'source_type' => 'AI_POLICY',
                'source_id' => null,
                'metadata' => [
                    'topic' => 'assistant_scope',
                ],
            ],
        ];


        foreach ($documents as $document) {
            KnowledgeDocument::updateOrCreate(
                [
                    'source_type' =>
                        $document['source_type'],

                    'title' =>
                        $document['title'],
                ],
                [
                    'source_id' =>
                        $document['source_id'],

                    'content' =>
                        $document['content'],

                    'embedding' =>
                        null,

                    'metadata' =>
                        $document['metadata'],

                    'is_active' =>
                        true,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Đồng bộ danh mục dịch vụ hiện có
        |--------------------------------------------------------------------------
        |
        | Đây là dữ liệu nghiệp vụ chung, phù hợp để đưa vào Knowledge Base.
        | Không đưa dữ liệu riêng của khách hàng vào đây.
        |
        */

        $services = Service::with('category')->get();


        foreach ($services as $service) {
            $categoryName =
                $service->category?->name
                ?? 'Chưa phân loại';


            $contentParts = [
                "Tên dịch vụ: {$service->name}.",
                "Danh mục: {$categoryName}.",
            ];


            if ($service->description) {
                $contentParts[] =
                    "Mô tả: {$service->description}.";
            }


            $contentParts[] =
                'Giá tham khảo: '
                . number_format(
                    $service->base_price,
                    0,
                    ',',
                    '.'
                )
                . ' đồng.';


            if (
                $service->estimated_duration_minutes
            ) {
                $contentParts[] =
                    'Thời gian thực hiện dự kiến: '
                    . $service->estimated_duration_minutes
                    . ' phút.';
            }


            if ($service->mileage_interval) {
                $contentParts[] =
                    'Chu kỳ tham khảo theo kilomet: '
                    . number_format(
                        $service->mileage_interval,
                        0,
                        ',',
                        '.'
                    )
                    . ' km.';
            }


            if ($service->month_interval) {
                $contentParts[] =
                    'Chu kỳ tham khảo theo thời gian: '
                    . $service->month_interval
                    . ' tháng.';
            }


            KnowledgeDocument::updateOrCreate(
                [
                    'source_type' => 'SERVICE',
                    'source_id' => $service->id,
                ],
                [
                    'title' => $service->name,

                    'content' => implode(
                        ' ',
                        $contentParts
                    ),

                    'embedding' => null,

                    'metadata' => [
                        'service_id' =>
                            $service->id,

                        'category' =>
                            $categoryName,

                        'base_price' =>
                            $service->base_price,

                        'mileage_interval' =>
                            $service
                                ->mileage_interval,

                        'month_interval' =>
                            $service
                                ->month_interval,

                        'estimated_duration_minutes' =>
                            $service
                                ->estimated_duration_minutes,
                    ],

                    'is_active' => true,
                ]
            );
        }
    }
}