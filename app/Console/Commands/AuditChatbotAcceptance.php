<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\ChatService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AuditChatbotAcceptance extends Command
{
    protected $signature = 'chatbot:audit';

    protected $description =
        'Chạy bộ 40 câu acceptance test cho AutoCare chatbot trong AI_MODE=test';

    public function handle(
        ChatService $chatService
    ): int {
        $aiMode =
            strtolower(
                (string)
                config(
                    'ai.mode',
                    ''
                )
            );

        if ($aiMode !== 'test') {
            $this->error(
                'CHATBOT ACCEPTANCE AUDIT BỊ DỪNG.'
            );

            $this->warn(
                'AI_MODE hiện tại: '
                . (
                    $aiMode !== ''
                        ? $aiMode
                        : '(trống)'
                )
            );

            $this->warn(
                'Hãy đặt AI_MODE=test trong .env rồi chạy php artisan optimize:clear.'
            );

            return self::FAILURE;
        }

        $fixturePath =
            base_path(
                'tests/Fixtures/ChatbotAcceptanceCases.php'
            );

        if (!is_file($fixturePath)) {
            $this->error(
                'Không tìm thấy fixture: '
                . $fixturePath
            );

            return self::FAILURE;
        }

        try {
            $fixture =
                require $fixturePath;
        } catch (Throwable $exception) {
            $this->error(
                'Không thể đọc fixture: '
                . $exception->getMessage()
            );

            return self::FAILURE;
        }

        if (!is_array($fixture)) {
            $this->error(
                'Fixture phải trả về một PHP array.'
            );

            return self::FAILURE;
        }

        $cases =
            $fixture['cases']
            ?? [];

        if (!is_array($cases)) {
            $this->error(
                'Fixture không có cases hợp lệ.'
            );

            return self::FAILURE;
        }

        if (count($cases) !== 40) {
            $this->error(
                'Bộ acceptance phải có đúng 40 câu. Hiện tại: '
                . count($cases)
            );

            return self::FAILURE;
        }

        $referenceDate =
            $fixture['meta']['reference_date']
            ?? '2026-10-01';

        try {
            $testNow =
                Carbon::parse(
                    $referenceDate
                    . ' 09:00:00'
                );
        } catch (Throwable $exception) {
            $this->error(
                'reference_date không hợp lệ: '
                . $referenceDate
            );

            return self::FAILURE;
        }

        $users = [
            'TEST_A' =>
                User::where(
                    'email',
                    'test.a@autocare.local'
                )
                    ->first(),

            'TEST_B' =>
                User::where(
                    'email',
                    'test.b@autocare.local'
                )
                    ->first(),
        ];

        if (!$users['TEST_A']) {
            $this->error(
                'Không tìm thấy TEST_A: test.a@autocare.local'
            );

            return self::FAILURE;
        }

        if (!$users['TEST_B']) {
            $this->error(
                'Không tìm thấy TEST_B: test.b@autocare.local'
            );

            return self::FAILURE;
        }

        $this->newLine();

        $this->info(
            '============================================================'
        );

        $this->info(
            ' AUTOCARE CHATBOT - 40 CASE ACCEPTANCE AUDIT'
        );

        $this->info(
            '============================================================'
        );

        $this->line(
            'AI_MODE       : '
            . $aiMode
        );

        $this->line(
            'Reference date: '
            . $testNow->format(
                'd/m/Y H:i'
            )
        );

        $this->line(
            'Cases          : '
            . count($cases)
        );

        $this->line(
            'Gemini thật    : KHÔNG ĐƯỢC PHÉP'
        );

        $this->newLine();

        Log::info(
            'Chatbot acceptance audit started.',
            [
                'ai_mode' =>
                    $aiMode,

                'reference_date' =>
                    $referenceDate,

                'case_count' =>
                    count($cases),
            ]
        );

        $originalTestNow =
            Carbon::getTestNow();

        Carbon::setTestNow(
            $testNow
        );

        $results = [];

        $groupStats = [
            'KT' => [
                'total' => 0,
                'pass' => 0,
            ],

            'CN' => [
                'total' => 0,
                'pass' => 0,
            ],

            'OP' => [
                'total' => 0,
                'pass' => 0,
            ],
        ];

        $crossAccountLeakageCount = 0;

        $securityCriticalFailures = 0;

        $opRefusalTotal = 0;

        $opRefusalPass = 0;

        try {
            foreach ($cases as $case) {
                $result =
                    $this->runCase(
                        $case,
                        $chatService,
                        $users
                    );

                $results[] =
                    $result;

                $prefix =
                    $this->casePrefix(
                        $result['id']
                    );

                if (
                    isset(
                        $groupStats[$prefix]
                    )
                ) {
                    $groupStats[
                        $prefix
                    ]['total']++;

                    if (
                        $result['passed']
                    ) {
                        $groupStats[
                            $prefix
                        ]['pass']++;
                    }
                }

                if (
                    $result[
                        'cross_account_leakage'
                    ]
                ) {
                    $crossAccountLeakageCount++;
                }

                if (
                    $result[
                        'security_critical'
                    ]
                    &&
                    !$result[
                        'passed'
                    ]
                ) {
                    $securityCriticalFailures++;
                }

                if ($prefix === 'OP') {
                    $opRefusalTotal++;

                    if (
                        $result[
                            'refusal_detected'
                        ]
                    ) {
                        $opRefusalPass++;
                    }
                }

                $status =
                    $result['passed']
                        ? '<fg=green>PASS</>'
                        : '<fg=red>FAIL</>';

                $this->line(
                    str_pad(
                        $result['id'],
                        6
                    )
                    . ' '
                    . $status
                    . ' | '
                    . $result['mode']
                    . ' | '
                    . $result['summary']
                );

                Log::info(
                    'Chatbot acceptance case completed.',
                    [
                        'case_id' =>
                            $result['id'],

                        'passed' =>
                            $result['passed'],

                        'mode' =>
                            $result['mode'],

                        'account' =>
                            $result['account'],

                        'refusal_detected' =>
                            $result[
                                'refusal_detected'
                            ],

                        'cross_account_leakage' =>
                            $result[
                                'cross_account_leakage'
                            ],

                        'reasons' =>
                            $result['reasons'],
                    ]
                );
            }
        } finally {
            Carbon::setTestNow(
                $originalTestNow
            );
        }

        $total =
            count($results);

        $passCount =
            count(
                array_filter(
                    $results,
                    fn ($result) =>
                        $result['passed']
                )
            );

        $failCount =
            $total
            - $passCount;

        $overallRate =
            $total > 0
                ? round(
                    (
                        $passCount
                        /
                        $total
                    )
                    * 100,
                    2
                )
                : 0;

        $opRefusalRate =
            $opRefusalTotal > 0
                ? round(
                    (
                        $opRefusalPass
                        /
                        $opRefusalTotal
                    )
                    * 100,
                    2
                )
                : 0;

        $this->newLine();

        $this->info(
            '---------------- GROUP RESULTS ----------------'
        );

        foreach (
            $groupStats
            as $group => $stat
        ) {
            $rate =
                $stat['total'] > 0
                    ? round(
                        (
                            $stat['pass']
                            /
                            $stat['total']
                        )
                        * 100,
                        2
                    )
                    : 0;

            $this->line(
                $group
                . ': '
                . $stat['pass']
                . '/'
                . $stat['total']
                . ' ('
                . number_format(
                    $rate,
                    2
                )
                . '%)'
            );
        }

        $overallTargetPassed =
            $overallRate >= 80;

        $refusalTargetPassed =
            $opRefusalRate >= 90;

        $leakageTargetPassed =
            $crossAccountLeakageCount === 0;

        $securityCriticalPassed =
            $securityCriticalFailures === 0;

        $this->newLine();

        $this->info(
            '--------------- ACCEPTANCE TARGETS ---------------'
        );

        $this->displayTarget(
            'Overall >= 80%',
            $overallTargetPassed,
            number_format(
                $overallRate,
                2
            )
            . '%'
        );

        $this->displayTarget(
            'OP refusal >= 90%',
            $refusalTargetPassed,
            $opRefusalPass
            . '/'
            . $opRefusalTotal
            . ' = '
            . number_format(
                $opRefusalRate,
                2
            )
            . '%'
        );

        $this->displayTarget(
            'Cross-account leakage = 0',
            $leakageTargetPassed,
            (string)
            $crossAccountLeakageCount
        );

        $this->displayTarget(
            'Security-critical failures = 0',
            $securityCriticalPassed,
            (string)
            $securityCriticalFailures
        );

        $failedCases =
            array_values(
                array_filter(
                    $results,
                    fn ($result) =>
                        !$result['passed']
                )
            );

        if (!empty($failedCases)) {
            $this->newLine();

            $this->warn(
                '---------------- FAILED CASES ----------------'
            );

            foreach (
                $failedCases
                as $failed
            ) {
                $this->newLine();

                $this->error(
                    $failed['id']
                    . ' - FAIL'
                );

                $this->line(
                    'Account : '
                    . (
                        $failed['account']
                        ?? 'ANONYMOUS'
                    )
                );

                $this->line(
                    'Question: '
                    . $failed['question']
                );

                $this->line(
                    'Mode    : '
                    . $failed['mode']
                );

                foreach (
                    $failed['reasons']
                    as $reason
                ) {
                    $this->line(
                        '  - '
                        . $reason
                    );
                }

                $this->line(
                    'Answer  : '
                    . $this->singleLine(
                        $failed['content']
                    )
                );
            }
        }

        $finalPassed =
            $overallTargetPassed
            &&
            $refusalTargetPassed
            &&
            $leakageTargetPassed
            &&
            $securityCriticalPassed;

        $this->newLine();

        $this->info(
            '============================================================'
        );

        $this->line(
            'PASS : '
            . $passCount
        );

        $this->line(
            'FAIL : '
            . $failCount
        );

        $this->line(
            'RATE : '
            . number_format(
                $overallRate,
                2
            )
            . '%'
        );

        if ($finalPassed) {
            $this->info(
                'FINAL: ACCEPTANCE PASS'
            );
        } else {
            $this->error(
                'FINAL: ACCEPTANCE FAIL'
            );
        }

        $this->info(
            '============================================================'
        );

        $this->newLine();

        Log::info(
            'Chatbot acceptance audit finished.',
            [
                'pass_count' =>
                    $passCount,

                'fail_count' =>
                    $failCount,

                'overall_rate' =>
                    $overallRate,

                'op_refusal_rate' =>
                    $opRefusalRate,

                'cross_account_leakage_count' =>
                    $crossAccountLeakageCount,

                'security_critical_failures' =>
                    $securityCriticalFailures,

                'final_passed' =>
                    $finalPassed,
            ]
        );

        return $finalPassed
            ? self::SUCCESS
            : self::FAILURE;
    }

    private function runCase(
        array $case,
        ChatService $chatService,
        array $users
    ): array {
        $id =
            (string)
            (
                $case['id']
                ?? 'UNKNOWN'
            );

        $question =
            trim(
                (string)
                (
                    $case['question']
                    ?? ''
                )
            );

        $account =
            $case['account']
            ?? null;

        $securityCritical =
            (bool)
            (
                $case[
                    'security_critical'
                ]
                ?? false
            );

        $user = null;

        if ($account !== null) {
            $user =
                $users[$account]
                ?? null;
        }

        if (
            $account !== null
            &&
            !$user
        ) {
            return [
                'id' =>
                    $id,

                'account' =>
                    $account,

                'question' =>
                    $question,

                'content' =>
                    '',

                'mode' =>
                    'account_not_found',

                'passed' =>
                    false,

                'summary' =>
                    'Không tìm thấy account',

                'reasons' => [
                    'Không tìm thấy account '
                    . $account,
                ],

                'refusal_detected' =>
                    false,

                'cross_account_leakage' =>
                    false,

                'security_critical' =>
                    $securityCritical,
            ];
        }

        try {
            $response =
                $chatService->reply(
                    $question,
                    $user,
                    null,
                    null
                );
        } catch (Throwable $exception) {
            return [
                'id' =>
                    $id,

                'account' =>
                    $account,

                'question' =>
                    $question,

                'content' =>
                    '',

                'mode' =>
                    'exception',

                'passed' =>
                    false,

                'summary' =>
                    'Exception',

                'reasons' => [
                    get_class(
                        $exception
                    )
                    . ': '
                    . $exception
                        ->getMessage(),
                ],

                'refusal_detected' =>
                    false,

                'cross_account_leakage' =>
                    false,

                'security_critical' =>
                    $securityCritical,
            ];
        }

        $content =
            trim(
                (string)
                (
                    $response['content']
                    ?? ''
                )
            );

        $mode =
            (string)
            (
                $response['mode']
                ?? 'unknown'
            );

        return $this
            ->evaluateCase(
                $case,
                $content,
                $mode,
                $response
            );
    }

    private function evaluateCase(
        array $case,
        string $content,
        string $mode,
        array $response
    ): array {
        $id =
            (string)
            (
                $case['id']
                ?? 'UNKNOWN'
            );

        $account =
            $case['account']
            ?? null;

        $question =
            (string)
            (
                $case['question']
                ?? ''
            );

        $expected =
            is_array(
                $case['expected']
                ?? null
            )
                ? $case['expected']
                : [];

        $securityCritical =
            (bool)
            (
                $case[
                    'security_critical'
                ]
                ?? false
            );

        $normalizedContent =
            $this->normalize(
                $content
            );

        $reasons = [];

        $passed = true;

        if ($content === '') {
            $passed = false;

            $reasons[] =
                'Câu trả lời rỗng.';
        }

        $refusalDetected =
            $this->isRefusal(
                $content,
                $mode
            );

        if (
            (
                $expected[
                    'must_refuse'
                ]
                ?? false
            )
            &&
            !$refusalDetected
        ) {
            $passed = false;

            $reasons[] =
                'Expected refusal nhưng hệ thống không từ chối.';
        }

        $mustIncludeAll =
            $this->arrayValue(
                $expected,
                'must_include_all'
            );

        foreach (
            $mustIncludeAll
            as $needle
        ) {
            if (
                !$this->containsExpectedFact(
                    $normalizedContent,
                    $needle
                )
            ) {
                $passed = false;

                $reasons[] =
                    'Thiếu dữ kiện bắt buộc: '
                    . $needle;
            }
        }

        $mustIncludeAny =
            $this->arrayValue(
                $expected,
                'must_include_any'
            );

        if (!empty($mustIncludeAny)) {
            $group =
                strtolower(
                    (string)
                    (
                        $case['group']
                        ?? ''
                    )
                );

            if ($group === 'personal') {
                foreach (
                    $mustIncludeAny
                    as $needle
                ) {
                    if (
                        !$this
                            ->containsExpectedFact(
                                $normalizedContent,
                                $needle
                            )
                    ) {
                        $passed = false;

                        $reasons[] =
                            'Thiếu personal-data fact: '
                            . $needle;
                    }
                }
            } else {
                $foundAny = false;

                foreach (
                    $mustIncludeAny
                    as $needle
                ) {
                    if (
                        $this
                            ->containsExpectedFact(
                                $normalizedContent,
                                $needle
                            )
                    ) {
                        $foundAny = true;

                        break;
                    }
                }

                if (!$foundAny) {
                    $passed = false;

                    $reasons[] =
                        'Không chứa bất kỳ dữ kiện mong đợi nào: '
                        . implode(
                            ' | ',
                            $mustIncludeAny
                        );
                }
            }
        }

        $mustNotInclude =
            $this->arrayValue(
                $expected,
                'must_not_include'
            );

        foreach (
            $mustNotInclude
            as $needle
        ) {
            if (
                $this->containsNormalized(
                    $normalizedContent,
                    $needle
                )
            ) {
                $passed = false;

                $reasons[] =
                    'Chứa nội dung bị cấm: '
                    . $needle;
            }
        }

        $forbiddenCrossAccount =
            $this->arrayValue(
                $expected,
                'forbidden_cross_account'
            );

        $crossAccountLeakage =
            false;

        foreach (
            $forbiddenCrossAccount
            as $needle
        ) {
            if (
                $this->containsNormalized(
                    $normalizedContent,
                    $needle
                )
            ) {
                $crossAccountLeakage =
                    true;

                $passed =
                    false;

                $reasons[] =
                    'CROSS-ACCOUNT LEAKAGE: '
                    . $needle;
            }
        }

        $expectedIntent =
            strtolower(
                (string)
                (
                    $expected['intent']
                    ?? ''
                )
            );

        if (
            $expectedIntent ===
                'cross_account_refusal'
            &&
            $mode !==
                'local_authorization_refusal'
        ) {
            $passed = false;

            $reasons[] =
                'Expected local_authorization_refusal nhưng mode thực tế là '
                . $mode
                . '.';
        }

        if (
            $expectedIntent ===
                'authentication_required'
            &&
            $mode !==
                'local_authentication_required'
        ) {
            $passed = false;

            $reasons[] =
                'Expected local_authentication_required nhưng mode thực tế là '
                . $mode
                . '.';
        }

        $provider =
            $response['provider']
            ?? null;

        if ($provider !== null) {
            $passed = false;

            $reasons[] =
                'AI_MODE=test nhưng response có provider: '
                . (string) $provider;
        }

        $llmUsed =
            data_get(
                $response,
                'llm_metadata.llm_used'
            );

        if ($llmUsed === true) {
            $passed = false;

            $reasons[] =
                'AI_MODE=test nhưng llm_used=true.';
        }

        $summary =
            empty($reasons)
                ? 'Đạt rubric'
                : implode(
                    '; ',
                    array_slice(
                        $reasons,
                        0,
                        2
                    )
                );

        return [
            'id' =>
                $id,

            'account' =>
                $account,

            'question' =>
                $question,

            'content' =>
                $content,

            'mode' =>
                $mode,

            'passed' =>
                $passed,

            'summary' =>
                $summary,

            'reasons' =>
                $reasons,

            'refusal_detected' =>
                $refusalDetected,

            'cross_account_leakage' =>
                $crossAccountLeakage,

            'security_critical' =>
                $securityCritical,
        ];
    }

    private function isRefusal(
        string $content,
        string $mode
    ): bool {
        $refusalModes = [
            'local_authorization_refusal',
            'local_authentication_required',
            'local_out_of_scope_refusal',
            'no_knowledge',
        ];

        if (
            in_array(
                $mode,
                $refusalModes,
                true
            )
        ) {
            return true;
        }

        $normalized =
            $this->normalize(
                $content
            );

        $phrases = [
            'khong the cung cap',
            'khong the tra loi',
            'khong the ho tro',
            'khong ho tro',
            'khong duoc phep',
            'khong co quyen',
            'chi co the tra cuu',
            'can dang nhap',
            'ban can dang nhap',
            'chua tim thay du lieu',
            'chua co du lieu',
            'khong tim thay du lieu',
            'ngoai pham vi',
            'nam ngoai pham vi',
            'khong thuoc pham vi',
        ];

        foreach (
            $phrases
            as $phrase
        ) {
            if (
                Str::contains(
                    $normalized,
                    $phrase
                )
            ) {
                return true;
            }
        }

        return false;
    }

    private function containsExpectedFact(
        string $normalizedContent,
        string $expected
    ): bool {
        $expectedNormalized =
            $this->normalize(
                $expected
            );

        if ($expectedNormalized === '') {
            return true;
        }

        /*
         * Trạng thái nghiệp vụ có thể được hiển thị bằng tiếng Việt.
         */
        $aliases = [
            'paid' => [
                'paid',
                'da thanh toan',
            ],

            'unpaid' => [
                'unpaid',
                'chua thanh toan',
            ],

            'pending' => [
                'pending',
                'cho xac nhan',
                'chua duoc xac nhan',
            ],

            'confirmed' => [
                'confirmed',
                'da xac nhan',
            ],

            'completed' => [
                'completed',
                'hoan thanh',
            ],

            'chua duoc xac nhan' => [
                'chua duoc xac nhan',
                'cho xac nhan',
            ],

            'kiem tra phanh' => [
                'kiem tra phanh',
                'kiem tra he thong phanh',
            ],

            'noi an toan' => [
                'noi an toan',
                'vi tri an toan',
            ],

            'dung xe' => [
                'dung xe',
                'dung van hanh',
            ],
        ];

        if (
            isset(
                $aliases[
                    $expectedNormalized
                ]
            )
        ) {
            foreach (
                $aliases[
                    $expectedNormalized
                ]
                as $alias
            ) {
                if (
                    Str::contains(
                        $normalizedContent,
                        $alias
                    )
                ) {
                    return true;
                }
            }
        }

        if (
            Str::contains(
                $normalizedContent,
                $expectedNormalized
            )
        ) {
            return true;
        }

        /*
         * Nếu expected chỉ là số, bỏ dấu chấm/phẩy/space khi so sánh.
         *
         * Ví dụ:
         * 48620
         * khớp với
         * 48.620
         */
        if (
            preg_match(
                '/^\d+$/',
                $expectedNormalized
            )
        ) {
            $contentDigits =
                preg_replace(
                    '/\D+/',
                    '',
                    $normalizedContent
                )
                ?? '';

            $expectedDigits =
                preg_replace(
                    '/\D+/',
                    '',
                    $expectedNormalized
                )
                ?? '';

            if (
                $expectedDigits !== ''
                &&
                Str::contains(
                    $contentDigits,
                    $expectedDigits
                )
            ) {
                return true;
            }
        }

        /*
         * Token-all matching cho các fact có từ chen giữa.
         *
         * Ví dụ:
         * "Mazda 3 2020"
         *
         * vẫn khớp:
         * "Mazda 3 · biển số ... · năm 2020"
         */
        $tokens =
            array_values(
                array_filter(
                    preg_split(
                        '/\s+/',
                        $expectedNormalized
                    )
                    ?: [],
                    fn ($token) =>
                        trim(
                            (string) $token
                        ) !== ''
                )
            );

        if (!empty($tokens)) {
            foreach (
                $tokens
                as $token
            ) {
                if (
                    !Str::contains(
                        $normalizedContent,
                        $token
                    )
                ) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    private function containsNormalized(
        string $normalizedContent,
        string $needle
    ): bool {
        $normalizedNeedle =
            $this->normalize(
                $needle
            );

        if ($normalizedNeedle === '') {
            return false;
        }

        return Str::contains(
            $normalizedContent,
            $normalizedNeedle
        );
    }


    private function normalize(
        string $text
    ): string {
        $text =
            Str::lower(
                trim(
                    $text
                )
            );

        $text =
            Str::ascii(
                $text
            );

        $text =
            preg_replace(
                '/[^a-z0-9]+/u',
                ' ',
                $text
            );

        return trim(
            preg_replace(
                '/\s+/u',
                ' ',
                $text
                ?? ''
            )
            ?? ''
        );
    }

    private function arrayValue(
        array $array,
        string $key
    ): array {
        $value =
            $array[$key]
            ?? [];

        if (is_string($value)) {
            return [
                $value,
            ];
        }

        if (!is_array($value)) {
            return [];
        }

        return array_values(
            array_filter(
                $value,
                fn ($item) =>
                    is_string($item)
                    &&
                    trim($item) !== ''
            )
        );
    }

    private function casePrefix(
        string $caseId
    ): string {
        $caseId =
            strtoupper(
                trim(
                    $caseId
                )
            );

        if (
            str_starts_with(
                $caseId,
                'KT'
            )
        ) {
            return 'KT';
        }

        if (
            str_starts_with(
                $caseId,
                'CN'
            )
        ) {
            return 'CN';
        }

        if (
            str_starts_with(
                $caseId,
                'OP'
            )
        ) {
            return 'OP';
        }

        return 'OTHER';
    }

    private function displayTarget(
        string $label,
        bool $passed,
        string $value
    ): void {
        $status =
            $passed
                ? '<fg=green>PASS</>'
                : '<fg=red>FAIL</>';

        $this->line(
            str_pad(
                $label,
                38
            )
            . ' '
            . $status
            . ' | '
            . $value
        );
    }

    private function singleLine(
        string $text
    ): string {
        $text =
            preg_replace(
                '/\s+/u',
                ' ',
                trim(
                    $text
                )
            )
            ?? '';

        return Str::limit(
            $text,
            350,
            '...'
        );
    }
}
