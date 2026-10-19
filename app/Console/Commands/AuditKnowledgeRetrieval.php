<?php

namespace App\Console\Commands;

use App\Models\KnowledgeDocument;
use App\Services\KnowledgeRetrievalService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class AuditKnowledgeRetrieval extends Command
{
    protected $signature =
        'knowledge:audit
        {--limit=3 : Số Knowledge Document lấy cho mỗi câu hỏi}';

    protected $description =
        'Audit AutoCare RAG retrieval across brands, maintenance topics and out-of-scope questions';


    public function handle(
        KnowledgeRetrievalService $retrievalService
    ): int {
        $limit =
            max(
                1,
                (int) $this->option(
                    'limit'
                )
            );


        /*
        |--------------------------------------------------------------------------
        | PRE-FLIGHT
        |--------------------------------------------------------------------------
        */

        $totalDocuments =
            KnowledgeDocument::query()
                ->where(
                    'is_active',
                    true
                )
                ->count();


        $embeddedDocuments =
            KnowledgeDocument::query()
                ->where(
                    'is_active',
                    true
                )
                ->whereNotNull(
                    'embedding'
                )
                ->count();


        $this->newLine();

        $this->info(
            'AUTOCARE KNOWLEDGE RETRIEVAL AUDIT'
        );

        $this->line(
            str_repeat(
                '=',
                72
            )
        );

        $this->line(
            "Knowledge Documents active : {$totalDocuments}"
        );

        $this->line(
            "Documents có embedding      : {$embeddedDocuments}"
        );


        if (
            $totalDocuments === 0
        ) {
            $this->error(
                'Knowledge Base đang rỗng.'
            );

            return self::FAILURE;
        }


        $aiMode =
            strtolower(
                trim(
                    (string) config(
                        'ai.mode',
                        'live'
                    )
                )
            );


        if (
            $aiMode !== 'test'
            &&
            $embeddedDocuments !== $totalDocuments
        ) {
            $this->warn(
                'Chưa phải toàn bộ Knowledge Document có embedding.'
            );

            $this->warn(
                'Hãy chạy: php artisan knowledge:embed'
            );

            return self::FAILURE;
        }


        if (
            $aiMode === 'test'
            &&
            $embeddedDocuments !== $totalDocuments
        ) {
            $this->warn(
                'AI_MODE=test: bỏ qua yêu cầu phải có đầy đủ embedding.'
            );

            $this->line(
                'Retrieval audit sẽ sử dụng lexical-only theo cấu hình TEST mode.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TEST CASES
        |--------------------------------------------------------------------------
        */

        $tests =
            array_merge(
                $this->brandTests(),
                $this->generalMaintenanceTests(),
                $this->outOfScopeTests()
            );


        $results = [];

        $passed = 0;

        $failed = 0;


        foreach (
            $tests
            as $index => $test
        ) {
            $number =
                $index + 1;


            $question =
                $test[
                    'question'
                ];


            $this->newLine();

            $this->line(
                "[{$number}/"
                . count(
                    $tests
                )
                . "] "
                . $question
            );


            try {
                $documents =
                    $retrievalService
                        ->retrieve(
                            $question,
                            $limit
                        );


                $evaluation =
                    $this->evaluate(
                        $test,
                        $documents
                    );


                if (
                    $evaluation[
                        'passed'
                    ]
                ) {
                    $passed++;

                    $this->info(
                        'PASS - '
                        . $evaluation[
                            'reason'
                        ]
                    );
                } else {
                    $failed++;

                    $this->error(
                        'FAIL - '
                        . $evaluation[
                            'reason'
                        ]
                    );
                }


                if (
                    $documents->isEmpty()
                ) {
                    $this->line(
                        '  Retrieval: []'
                    );
                } else {
                    foreach (
                        $documents
                            ->take(
                                $limit
                            )
                        as $rank => $document
                    ) {
                        $metadata =
                            is_array(
                                $document->metadata
                            )
                                ? $document->metadata
                                : [];


                        $brand =
                            $metadata[
                                'brand'
                            ]
                            ?? 'GENERAL';


                        $scope =
                            $metadata[
                                'scope'
                            ]
                            ?? '-';


                        $this->line(
                            '  '
                            . (
                                $rank + 1
                            )
                            . '. '
                            . $document->title
                            . ' | '
                            . $brand
                            . ' | '
                            . $scope
                        );
                    }
                }


                $results[] = [
                    'group' =>
                        $test[
                            'group'
                        ],

                    'question' =>
                        $question,

                    'result' =>
                        $evaluation[
                            'passed'
                        ]
                            ? 'PASS'
                            : 'FAIL',

                    'top_document' =>
                        $documents
                            ->first()
                            ?->title
                        ?? '[]',
                ];
            } catch (
                Throwable $exception
            ) {
                report(
                    $exception
                );


                $failed++;


                $this->error(
                    'ERROR - '
                    . $exception
                        ->getMessage()
                );


                $results[] = [
                    'group' =>
                        $test[
                            'group'
                        ],

                    'question' =>
                        $question,

                    'result' =>
                        'ERROR',

                    'top_document' =>
                        '-',
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $this->newLine(2);

        $this->line(
            str_repeat(
                '=',
                72
            )
        );

        $this->info(
            'KẾT QUẢ KIỂM THỬ'
        );


        $this->table(
            [
                'Nhóm',
                'Câu hỏi',
                'Kết quả',
                'Top document',
            ],
            collect(
                $results
            )
                ->map(
                    fn (
                        array $result
                    ) => [
                        $result[
                            'group'
                        ],

                        $result[
                            'question'
                        ],

                        $result[
                            'result'
                        ],

                        $result[
                            'top_document'
                        ],
                    ]
                )
                ->all()
        );


        $total =
            count(
                $tests
            );


        $rate =
            $total > 0
                ? round(
                    (
                        $passed / $total
                    ) * 100,
                    2
                )
                : 0;


        $this->newLine();

        $this->line(
            "Tổng test : {$total}"
        );

        $this->info(
            "PASS      : {$passed}"
        );


        if (
            $failed > 0
        ) {
            $this->error(
                "FAIL      : {$failed}"
            );
        } else {
            $this->line(
                'FAIL      : 0'
            );
        }


        $this->line(
            "Tỷ lệ PASS: {$rate}%"
        );


        /*
         * Mục tiêu bước AI2D.5:
         * tất cả test retrieval nền phải PASS.
         */
        if (
            $failed > 0
        ) {
            $this->newLine();

            $this->warn(
                'Knowledge Retrieval chưa đạt yêu cầu.'
            );

            $this->warn(
                'Không commit trước khi xử lý các test FAIL.'
            );

            return self::FAILURE;
        }


        $this->newLine();

        $this->info(
            'AI2D.5 PASS - Brand-aware RAG hoạt động đúng trên bộ test hiện tại.'
        );


        return self::SUCCESS;
    }


    /*
    |--------------------------------------------------------------------------
    | BRAND-SPECIFIC TESTS
    |--------------------------------------------------------------------------
    */

    private function brandTests(): array
    {
        return [

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Toyota bao lâu bảo dưỡng định kỳ?',

                'expected_titles' => [
                    'Toyota - Chu kỳ bảo dưỡng 5.000 km hoặc 6 tháng',
                ],

                'expected_brand' =>
                    'Toyota',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Honda bao lâu bảo dưỡng định kỳ?',

                'expected_titles' => [
                    'Honda - Chu kỳ bảo dưỡng 5.000 km hoặc 6 tháng',
                ],

                'expected_brand' =>
                    'Honda',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Mazda mốc bảo dưỡng đầu tiên là khi nào?',

                'expected_titles' => [
                    'Mazda - Kiểm tra 1.000 km đầu tiên',
                    'Mazda - Bảo dưỡng cấp 1 tại 3 tháng hoặc 5.000 km',
                ],

                'expected_brand' =>
                    'Mazda',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Mitsubishi bao lâu bảo dưỡng định kỳ?',

                'expected_titles' => [
                    'Mitsubishi - Chu kỳ bảo dưỡng 5.000 km hoặc 3 tháng',
                ],

                'expected_brand' =>
                    'Mitsubishi',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Nissan bao lâu bảo dưỡng định kỳ?',

                'expected_titles' => [
                    'Nissan - Các lần bảo dưỡng tiếp theo 5.000 km hoặc 3 tháng',
                    'Nissan - Bảo dưỡng đầu tiên 1.000 km hoặc 1 tháng',
                ],

                'expected_brand' =>
                    'Nissan',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Hyundai bao lâu bảo dưỡng định kỳ?',

                'expected_titles' => [
                    'Hyundai - Bảo dưỡng tiêu chuẩn 5.000 km hoặc 6 tháng',
                    'Hyundai - Bảo dưỡng đầu tiên 1.000 km hoặc 1 tháng',
                ],

                'expected_brand' =>
                    'Hyundai',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Kia bảo dưỡng cần kiểm tra những gì?',

                'expected_titles' => [
                    'Kia - Các hạng mục kiểm tra bảo dưỡng cơ bản',
                    'Kia - Bảo dưỡng được tổ chức theo nhiều cấp',
                ],

                'expected_brand' =>
                    'Kia',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Ford bao lâu thay dầu động cơ?',

                'expected_titles' => [
                    'Ford - Chu kỳ thay dầu tiêu chuẩn trên các dòng xe thế hệ mới',
                    'Ford - Hệ thống theo dõi tuổi thọ dầu có thể yêu cầu thay sớm hơn',
                ],

                'expected_brand' =>
                    'Ford',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'VinFast xe điện bao lâu bảo dưỡng?',

                'expected_titles' => [
                    'VinFast - Chu kỳ bảo dưỡng ô tô điện từ 01/01/2026',
                ],

                'expected_brand' =>
                    'VinFast',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'Mercedes-Benz xác định lịch bảo dưỡng như thế nào?',

                'expected_titles' => [
                    'Mercedes-Benz - ASSYST PLUS theo dõi thời gian và quãng đường còn lại',
                    'Mercedes-Benz - Điều kiện sử dụng có thể rút ngắn chu kỳ dịch vụ',
                ],

                'expected_brand' =>
                    'Mercedes-Benz',
            ],

            [
                'group' =>
                    'BRAND',

                'question' =>
                    'BMW xác định khi nào cần bảo dưỡng như thế nào?',

                'expected_titles' => [
                    'BMW - Condition Based Service',
                    'BMW - Proactive Care hỗ trợ phát hiện nhu cầu dịch vụ',
                ],

                'expected_brand' =>
                    'BMW',
            ],

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | GENERAL FALLBACK TESTS
    |--------------------------------------------------------------------------
    |
    | Mỗi câu cố tình gắn một hãng.
    |
    | Nếu hãng chưa có bài OEM riêng về vấn đề đó,
    | GENERAL_GUIDE phải giúp chatbot trả lời.
    |
    */

    private function generalMaintenanceTests(): array
    {
        return [

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Kia dầu động cơ có tác dụng gì?',

                'expected_titles' => [
                    'Dầu động cơ có vai trò gì?',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'BMW lốp mòn không đều thì nên kiểm tra gì?',

                'expected_titles' => [
                    'Mòn lốp không đều có thể liên quan đến hệ thống treo hoặc góc đặt bánh xe',
                    'Dấu hiệu lốp cần được kiểm tra',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Kia phanh kêu thì kiểm tra gì?',

                'expected_titles' => [
                    'Phanh phát tiếng kêu cần kiểm tra gì?',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Toyota ắc quy 12V yếu có dấu hiệu gì?',

                'expected_titles' => [
                    'Dấu hiệu ắc quy 12V yếu',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Honda bugi hỏng có dấu hiệu gì?',

                'expected_titles' => [
                    'Dấu hiệu bugi có thể cần kiểm tra',
                    'Chức năng của bugi',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Ford bị rò nước làm mát thì cần kiểm tra gì?',

                'expected_titles' => [
                    'Dấu hiệu rò rỉ hệ thống làm mát',
                    'Vai trò của hệ thống làm mát động cơ',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Hyundai bị nóng máy thì phải làm gì?',

                'expected_titles' => [
                    'Xử lý ban đầu khi xe có dấu hiệu quá nhiệt',
                    'Dấu hiệu động cơ quá nhiệt',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Nissan dây curoa phát tiếng kêu thì sao?',

                'expected_titles' => [
                    'Dấu hiệu dây đai phụ cần kiểm tra',
                    'Bộ tăng đai và puly cũng ảnh hưởng đến dây đai',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Mitsubishi điều hòa không mát thì kiểm tra gì?',

                'expected_titles' => [
                    'Điều hòa ô tô không mát cần kiểm tra nhiều thành phần',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Mazda lọc gió điều hòa bẩn có ảnh hưởng gì?',

                'expected_titles' => [
                    'Lọc gió điều hòa ảnh hưởng đến luồng gió trong cabin',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Ford hộp số sang số bị giật thì sao?',

                'expected_titles' => [
                    'Sang số bất thường cần được kiểm tra',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Mercedes-Benz xe bị rung khi chạy thì kiểm tra gì?',

                'expected_titles' => [
                    'Xe rung không đồng nghĩa chắc chắn hỏng giảm xóc',
                    'Dấu hiệu giảm xóc hoặc hệ thống treo cần kiểm tra',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Honda đèn Check Engine sáng thì phải làm gì?',

                'expected_titles' => [
                    'Đèn Check Engine sáng cần được chẩn đoán',
                    'Đèn cảnh báo đi kèm triệu chứng nghiêm trọng cần ưu tiên an toàn',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'Nissan bị ngập nước thì phải làm gì?',

                'expected_titles' => [
                    'Xe bị ngập nước không nên cố khởi động lại',
                    'Xe sau ngập cần được kiểm tra nhiều hệ thống',
                ],
            ],

            [
                'group' =>
                    'GENERAL',

                'question' =>
                    'VinFast xe điện có cần kiểm tra phanh không?',

                'expected_titles' => [
                    'Phanh tái sinh không loại bỏ nhu cầu kiểm tra phanh cơ khí',
                    'Xe điện vẫn cần bảo dưỡng định kỳ',
                    'VinFast - Các hệ thống xe điện cần được kiểm tra định kỳ',
                ],
            ],

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | OUT-OF-SCOPE TESTS
    |--------------------------------------------------------------------------
    */

    private function outOfScopeTests(): array
    {
        return [

            [
                'group' =>
                    'OUT_OF_SCOPE',

                'question' =>
                    'Cách nấu phở bò ngon?',

                'expect_empty' =>
                    true,
            ],

            [
                'group' =>
                    'OUT_OF_SCOPE',

                'question' =>
                    'Laptop gaming nào tốt?',

                'expect_empty' =>
                    true,
            ],

            [
                'group' =>
                    'OUT_OF_SCOPE',

                'question' =>
                    'Tỷ giá đô la hôm nay bao nhiêu?',

                'expect_empty' =>
                    true,
            ],

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | EVALUATION
    |--------------------------------------------------------------------------
    */

    private function evaluate(
        array $test,
        Collection $documents
    ): array {
        /*
        |--------------------------------------------------------------------------
        | OUT OF SCOPE
        |--------------------------------------------------------------------------
        */

        if (
            ($test['expect_empty'] ?? false) === true
        ) {
            if (
                $documents->isEmpty()
            ) {
                return [
                    'passed' =>
                        true,

                    'reason' =>
                        'Câu ngoài phạm vi đã bị từ chối retrieval.',
                ];
            }


            return [
                'passed' =>
                    false,

                'reason' =>
                    'Câu ngoài phạm vi vẫn lấy được tài liệu: '
                    . (
                        $documents
                            ->first()
                            ?->title
                        ?? '-'
                    ),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | MUST HAVE DOCUMENT
        |--------------------------------------------------------------------------
        */

        if (
            $documents->isEmpty()
        ) {
            return [
                'passed' =>
                    false,

                'reason' =>
                    'Không lấy được tài liệu nào.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | EXPECTED TITLE
        |--------------------------------------------------------------------------
        */

        $expectedTitles =
            $test[
                'expected_titles'
            ]
            ?? [];


        $titleMatched =
            $documents
                ->contains(
                    function (
                        KnowledgeDocument $document
                    ) use (
                        $expectedTitles
                    ) {
                        foreach (
                            $expectedTitles
                            as $expectedTitle
                        ) {
                            if (
                                Str::contains(
                                    Str::lower(
                                        $document->title
                                    ),
                                    Str::lower(
                                        $expectedTitle
                                    )
                                )
                            ) {
                                return true;
                            }
                        }


                        return false;
                    }
                );


        if (!$titleMatched) {
            return [
                'passed' =>
                    false,

                'reason' =>
                    'Không xuất hiện tài liệu mong đợi trong Top '
                    . $documents->count()
                    . '.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | BRAND CONTAMINATION CHECK
        |--------------------------------------------------------------------------
        |
        | Nếu query yêu cầu Honda:
        |
        | được phép:
        | Honda
        | GENERAL
        |
        | không được:
        | Toyota
        | Mazda
        | Ford...
        |
        */

        $expectedBrand =
            $test[
                'expected_brand'
            ]
            ?? null;


        if ($expectedBrand) {
            $foreignBrand =
                $documents
                    ->first(
                        function (
                            KnowledgeDocument $document
                        ) use (
                            $expectedBrand
                        ) {
                            $metadata =
                                is_array(
                                    $document->metadata
                                )
                                    ? $document->metadata
                                    : [];


                            $brand =
                                $metadata[
                                    'brand'
                                ]
                                ?? null;


                            if (
                                blank(
                                    $brand
                                )
                            ) {
                                return false;
                            }


                            $normalizedBrand =
                                Str::lower(
                                    trim(
                                        (string) $brand
                                    )
                                );


                            $normalizedExpectedBrand =
                                Str::lower(
                                    trim(
                                        $expectedBrand
                                    )
                                );


                            return
                                $normalizedBrand !== $normalizedExpectedBrand
                                &&
                                $normalizedBrand !== 'general';
                        }
                    );


            if ($foreignBrand) {
                $metadata =
                    is_array(
                        $foreignBrand->metadata
                    )
                        ? $foreignBrand->metadata
                        : [];


                return [
                    'passed' =>
                        false,

                    'reason' =>
                        'Lẫn dữ liệu hãng khác: '
                        . (
                            $metadata[
                                'brand'
                            ]
                            ?? '-'
                        )
                        . ' - '
                        . $foreignBrand->title,
                ];
            }


            $hasExpectedBrand =
                $documents
                    ->contains(
                        function (
                            KnowledgeDocument $document
                        ) use (
                            $expectedBrand
                        ) {
                            $metadata =
                                is_array(
                                    $document->metadata
                                )
                                    ? $document->metadata
                                    : [];


                            return
                                Str::lower(
                                    trim(
                                        (string) (
                                            $metadata[
                                                'brand'
                                            ]
                                            ?? ''
                                        )
                                    )
                                )
                                ===
                                Str::lower(
                                    trim(
                                        $expectedBrand
                                    )
                                );
                        }
                    );


            if (!$hasExpectedBrand) {
                return [
                    'passed' =>
                        false,

                    'reason' =>
                        'Không ưu tiên được tài liệu của hãng '
                        . $expectedBrand
                        . '.',
                ];
            }
        }


        return [
            'passed' =>
                true,

            'reason' =>
                'Retrieval đúng.',
        ];
    }
}