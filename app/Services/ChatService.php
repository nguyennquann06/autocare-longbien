<?php

namespace App\Services;

use App\Models\KnowledgeDocument;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ChatService
{
    public function __construct(
        private CustomerContextService
            $customerContextService
    ) {
    }


    /**
     * Sinh câu trả lời AutoCare.
     *
     * Luồng hiện tại:
     *
     * 1. Greeting
     * 2. Structured CUSTOMER Data
     * 3. Knowledge Base Search
     *
     * Bước sau:
     * 4. Embedding + RAG + LLM
     */
    public function reply(
        string $message,
        ?User $user = null
    ): array {
        $message =
            trim($message);


        /*
        |--------------------------------------------------------------------------
        | GREETING
        |--------------------------------------------------------------------------
        */

        if (
            $this->isGreeting(
                $message
            )
        ) {
            $name =
                $user?->name;


            $content =
                $name
                    ? "Xin chào {$name}! Mình là AutoCare AI."
                    : 'Xin chào! Mình là AutoCare AI.';


            $content .= "\n\n";


            $content .= implode(
                "\n",
                [
                    'Mình có thể hỗ trợ bạn về:',
                    '• Dịch vụ và giá tham khảo tại AutoCare.',
                    '• Chu kỳ bảo dưỡng theo kilomet hoặc thời gian.',
                    '• Quy trình đặt lịch và bảo dưỡng.',
                    '• Xe và ODO trong tài khoản CUSTOMER.',
                    '• Lịch hẹn sắp tới.',
                    '• Lịch sử bảo dưỡng.',
                    '• Hóa đơn chưa thanh toán.',
                    '• Gợi ý kỳ bảo dưỡng tiếp theo từ dữ liệu thực tế.',
                ]
            );


            return [
                'content' =>
                    $content,

                'sources' => [],

                'mode' =>
                    'fallback',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | STRUCTURED CUSTOMER DATA
        |--------------------------------------------------------------------------
        |
        | Dữ liệu riêng tư KHÔNG được đưa
        | vào knowledge_documents / embeddings.
        |
        | Nó được truy vấn trực tiếp từ DB
        | theo user đang đăng nhập.
        |
        */

        if ($user) {
            $customerAnswer =
                $this
                    ->customerContextService
                    ->answer(
                        $user,
                        $message
                    );


            if ($customerAnswer) {
                return $customerAnswer;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | KNOWLEDGE BASE SEARCH
        |--------------------------------------------------------------------------
        */

        $documents =
            KnowledgeDocument::query()
                ->where(
                    'is_active',
                    true
                )
                ->get([
                    'id',
                    'title',
                    'content',
                    'source_type',
                    'source_id',
                    'metadata',
                ]);


        $rankedDocuments =
            $this->rankDocuments(
                $message,
                $documents
            );


        if (
            $rankedDocuments
                ->isEmpty()
        ) {
            return [
                'content' => implode(
                    "\n\n",
                    [
                        'Mình chưa tìm thấy thông tin đủ phù hợp trong kho kiến thức AutoCare để trả lời chính xác câu hỏi này.',
                        'Bạn có thể thử hỏi cụ thể hơn, ví dụ:',
                        '• Thay dầu động cơ giá bao nhiêu?'
                            . "\n"
                            . '• Bao lâu nên bảo dưỡng xe?'
                            . "\n"
                            . '• Quy trình đặt lịch bảo dưỡng như thế nào?'
                            . "\n"
                            . '• Xe của tôi là xe gì?'
                            . "\n"
                            . '• Tôi còn hóa đơn nào chưa thanh toán?',
                    ]
                ),

                'sources' => [],

                'mode' =>
                    'fallback',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | BUILD KNOWLEDGE ANSWER
        |--------------------------------------------------------------------------
        */

        $answerParts = [
            'Dựa trên dữ liệu hiện có của AutoCare:',
        ];


        $sources = [];


        foreach (
            $rankedDocuments
            as $document
        ) {
            $answerParts[] =
                $document->title
                . "\n"
                . $document->content;


            $sources[] = [
                'id' =>
                    $document->id,

                'title' =>
                    $document->title,

                'source_type' =>
                    $document
                        ->source_type,

                'source_id' =>
                    $document
                        ->source_id,
            ];
        }


        $answerParts[] =
            implode(
                ' ',
                [
                    'Thông tin trên được lấy từ Knowledge Base của AutoCare.',
                    'Giá và chu kỳ bảo dưỡng mang tính tham khảo;',
                    'tình trạng thực tế của xe có thể cần được kỹ thuật viên kiểm tra trực tiếp.',
                ]
            );


        return [
            'content' =>
                implode(
                    "\n\n",
                    $answerParts
                ),

            'sources' =>
                $sources,

            'mode' =>
                'fallback',
        ];
    }


    /**
     * Xếp hạng tài liệu
     * theo độ liên quan.
     */
    private function rankDocuments(
        string $message,
        Collection $documents
    ): Collection {
        $normalizedQuestion =
            $this->normalize(
                $message
            );


        $keywords =
            $this->extractKeywords(
                $normalizedQuestion
            );


        return $documents
            ->map(
                function (
                    KnowledgeDocument $document
                ) use (
                    $normalizedQuestion,
                    $keywords
                ) {
                    $title =
                        $this->normalize(
                            $document->title
                        );


                    $content =
                        $this->normalize(
                            $document->content
                        );


                    $score = 0;


                    /**
                     * Khớp cả câu.
                     */
                    if (
                        $normalizedQuestion !== ''
                        &&
                        Str::contains(
                            $title,
                            $normalizedQuestion
                        )
                    ) {
                        $score += 15;
                    }


                    if (
                        $normalizedQuestion !== ''
                        &&
                        Str::contains(
                            $content,
                            $normalizedQuestion
                        )
                    ) {
                        $score += 8;
                    }


                    /**
                     * Khớp từ khóa.
                     */
                    foreach (
                        $keywords
                        as $keyword
                    ) {
                        if (
                            Str::contains(
                                $title,
                                $keyword
                            )
                        ) {
                            $score += 5;
                        }


                        if (
                            Str::contains(
                                $content,
                                $keyword
                            )
                        ) {
                            $score += 2;
                        }
                    }


                    /**
                     * Câu hỏi giá:
                     * ưu tiên SERVICE.
                     */
                    if (
                        $this->containsAny(
                            $normalizedQuestion,
                            [
                                'giá',
                                'bao nhiêu',
                                'chi phí',
                                'tiền',
                            ]
                        )
                        &&
                        $document
                            ->source_type
                        === 'SERVICE'
                    ) {
                        $score += 5;
                    }


                    /**
                     * Câu hỏi dịch vụ:
                     * ưu tiên SERVICE.
                     */
                    if (
                        $this->containsAny(
                            $normalizedQuestion,
                            [
                                'dịch vụ',
                                'bảo dưỡng',
                                'thay',
                                'kiểm tra',
                            ]
                        )
                        &&
                        $document
                            ->source_type
                        === 'SERVICE'
                    ) {
                        $score += 3;
                    }


                    $document
                        ->relevance_score =
                        $score;


                    return $document;
                }
            )
            ->filter(
                fn (
                    KnowledgeDocument $document
                ) =>
                    $document
                        ->relevance_score
                    > 0
            )
            ->sortByDesc(
                'relevance_score'
            )
            ->take(3)
            ->values();
    }


    /**
     * Chuẩn hóa chuỗi
     * phục vụ Knowledge Search.
     */
    private function normalize(
        string $text
    ): string {
        $text =
            Str::lower(
                trim($text)
            );


        $text =
            preg_replace(
                '/\s+/u',
                ' ',
                $text
            );


        return trim(
            $text ?? ''
        );
    }


    /**
     * Tách keyword đơn giản.
     *
     * Bước này chưa phải embedding.
     */
    private function extractKeywords(
        string $message
    ): array {
        preg_match_all(
            '/[\p{L}\p{N}]+/u',
            $message,
            $matches
        );


        $stopWords = [
            'là',
            'và',
            'của',
            'cho',
            'tôi',
            'mình',
            'bạn',
            'có',
            'không',
            'được',
            'như',
            'thế',
            'nào',
            'bao',
            'về',
            'với',
            'ở',
            'trong',
            'một',
            'những',
            'các',
            'thì',
            'nên',
            'cần',
            'muốn',
            'hỏi',
            'giúp',
        ];


        return collect(
            $matches[0]
            ?? []
        )
            ->map(
                fn ($word) =>
                    $this->normalize(
                        $word
                    )
            )
            ->filter(
                fn ($word) =>
                    mb_strlen(
                        $word
                    )
                    >= 2
                    &&
                    !in_array(
                        $word,
                        $stopWords,
                        true
                    )
            )
            ->unique()
            ->values()
            ->all();
    }


    /**
     * Nhận diện greeting.
     */
    private function isGreeting(
        string $message
    ): bool {
        $message =
            $this->normalize(
                $message
            );


        return in_array(
            $message,
            [
                'hi',
                'hello',
                'hey',
                'chào',
                'xin chào',
                'chào bạn',
                'hello bạn',
            ],
            true
        );
    }


    private function containsAny(
        string $text,
        array $phrases
    ): bool {
        foreach ($phrases as $phrase) {
            if (
                Str::contains(
                    $text,
                    $phrase
                )
            ) {
                return true;
            }
        }


        return false;
    }
}