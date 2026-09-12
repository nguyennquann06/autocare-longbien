<?php

namespace App\Services;

use App\Models\KnowledgeDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class KnowledgeRetrievalService
{
    public function __construct(
        private GeminiEmbeddingService
            $embeddingService
    ) {
    }


    /**
     * Hybrid Retrieval:
     *
     * Query Embedding
     * + Cosine Similarity
     * + Lexical Match
     * = Hybrid Ranking
     */
    public function retrieve(
        string $question,
        ?int $limit = null
    ): Collection {
        $limit =
            max(
                1,
                $limit
                ?? (int) config(
                    'ai.rag.top_k',
                    5
                )
            );

        $documents =
            KnowledgeDocument::query()
                ->when(
                    config(
                        'ai.rag.active_only',
                        true
                    ),
                    fn ($query) =>
                        $query->where(
                            'is_active',
                            true
                        )
                )
                ->get([
                    'id',
                    'title',
                    'content',
                    'source_type',
                    'source_id',
                    'embedding',
                    'metadata',
                ]);

        if ($documents->isEmpty()) {
            return collect();
        }

        $normalizedQuestion =
            $this->normalize(
                $question
            );

        $keywords =
            $this->extractKeywords(
                $normalizedQuestion
            );

        /*
        |--------------------------------------------------------------------------
        | LEXICAL SCORE
        |--------------------------------------------------------------------------
        */

        $documents =
            $documents->map(
                function (
                    KnowledgeDocument $document
                ) use (
                    $normalizedQuestion,
                    $keywords
                ) {
                    $document->lexical_score =
                        $this
                            ->calculateLexicalScore(
                                $document,
                                $normalizedQuestion,
                                $keywords
                            );

                    $document->semantic_score =
                        null;

                    $document->hybrid_score =
                        null;

                    $document->retrieval_mode =
                        'lexical';

                    return $document;
                }
            );

        $hasEmbeddings =
            $documents->contains(
                fn (
                    KnowledgeDocument $document
                ) =>
                    is_array(
                        $document->embedding
                    )
                    &&
                    !empty(
                        $document->embedding
                    )
            );

        if (
            !$this
                ->embeddingService
                ->isConfigured()
            ||
            !$hasEmbeddings
        ) {
            return $this
                ->rankLexically(
                    $documents,
                    $limit
                );
        }

        try {
            /*
             * QUAN TRỌNG:
             *
             * Query dùng format:
             *
             * task: search result | query: ...
             */
            $queryEmbedding =
                $this
                    ->embeddingService
                    ->embedQuery(
                        $question
                    );

            if (empty($queryEmbedding)) {
                return $this
                    ->rankLexically(
                        $documents,
                        $limit
                    );
            }

            return $this
                ->rankHybrid(
                    $documents,
                    $queryEmbedding,
                    $limit
                );
        } catch (Throwable $exception) {
            report($exception);

            return $this
                ->rankLexically(
                    $documents,
                    $limit
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HYBRID RANKING
    |--------------------------------------------------------------------------
    */

    private function rankHybrid(
        Collection $documents,
        array $queryEmbedding,
        int $limit
    ): Collection {
        /*
         * Semantic giữ vai trò chính.
         *
         * 85% semantic
         * 15% lexical
         */
        $semanticWeight = 0.85;
        $lexicalWeight = 0.15;

        $semanticMinScore =
            (float) config(
                'ai.rag.semantic_min_score',
                0.35
            );

        /*
         * Tính semantic score trước.
         */
        $documents =
            $documents->map(
                function (
                    KnowledgeDocument $document
                ) use (
                    $queryEmbedding
                ) {
                    $document->semantic_score =
                        $this->cosineSimilarity(
                            $queryEmbedding,
                            $document->embedding
                        );

                    return $document;
                }
            );

        /*
         * Normalize lexical score.
         */
        $maxLexicalScore =
            (float)
            $documents->max(
                'lexical_score'
            );

        if ($maxLexicalScore <= 0) {
            $maxLexicalScore = 1;
        }

        $ranked =
            $documents
                ->map(
                    function (
                        KnowledgeDocument $document
                    ) use (
                        $semanticWeight,
                        $lexicalWeight,
                        $maxLexicalScore
                    ) {
                        $semanticScore =
                            $document
                                ->semantic_score;

                        $normalizedSemantic =
                            $semanticScore !== null
                                ? min(
                                    1,
                                    max(
                                        0,
                                        (float)
                                        $semanticScore
                                    )
                                )
                                : 0;

                        $lexicalScore =
                            (float)
                            (
                                $document
                                    ->lexical_score
                                ?? 0
                            );

                        $normalizedLexical =
                            min(
                                1,
                                max(
                                    0,
                                    $lexicalScore
                                    /
                                    $maxLexicalScore
                                )
                            );

                        $hybridScore =
                            (
                                $normalizedSemantic
                                *
                                $semanticWeight
                            )
                            +
                            (
                                $normalizedLexical
                                *
                                $lexicalWeight
                            );

                        $document->hybrid_score =
                            $hybridScore;

                        $document->retrieval_mode =
                            'hybrid';

                        return $document;
                    }
                )
                ->filter(
                    function (
                        KnowledgeDocument $document
                    ) use (
                        $semanticMinScore
                    ) {
                        /*
                         * Semantic phải đủ tốt
                         * hoặc lexical phải thực sự
                         * có tín hiệu rõ ràng.
                         */
                        $semanticRelevant =
                            $document
                                ->semantic_score
                            !== null
                            &&
                            $document
                                ->semantic_score
                            >=
                            $semanticMinScore;

                        $lexicalRelevant =
                            (
                                $document
                                    ->lexical_score
                                ?? 0
                            )
                            >= 8;

                        return
                            $semanticRelevant
                            ||
                            $lexicalRelevant;
                    }
                )
                ->sort(
                    function (
                        KnowledgeDocument $a,
                        KnowledgeDocument $b
                    ) {
                        /*
                         * Hybrid score trước.
                         */
                        $hybridCompare =
                            (
                                $b
                                    ->hybrid_score
                                ?? 0
                            )
                            <=>
                            (
                                $a
                                    ->hybrid_score
                                ?? 0
                            );

                        if (
                            $hybridCompare !== 0
                        ) {
                            return
                                $hybridCompare;
                        }

                        /*
                         * Nếu bằng điểm,
                         * semantic thắng.
                         */
                        return
                            (
                                $b
                                    ->semantic_score
                                ?? 0
                            )
                            <=>
                            (
                                $a
                                    ->semantic_score
                                ?? 0
                            );
                    }
                )
                ->take(
                    $limit
                )
                ->values();

        if ($ranked->isEmpty()) {
            return $this
                ->rankLexically(
                    $documents,
                    $limit
                );
        }

        return $ranked;
    }


    /*
    |--------------------------------------------------------------------------
    | LEXICAL FALLBACK
    |--------------------------------------------------------------------------
    */

    private function rankLexically(
        Collection $documents,
        int $limit
    ): Collection {
        return $documents
            ->filter(
                fn (
                    KnowledgeDocument $document
                ) =>
                    (
                        $document
                            ->lexical_score
                        ?? 0
                    )
                    > 0
            )
            ->sortByDesc(
                'lexical_score'
            )
            ->take(
                $limit
            )
            ->map(
                function (
                    KnowledgeDocument $document
                ) {
                    $document->semantic_score =
                        null;

                    $document->hybrid_score =
                        null;

                    $document->retrieval_mode =
                        'lexical';

                    return $document;
                }
            )
            ->values();
    }


    /*
    |--------------------------------------------------------------------------
    | LEXICAL SCORING
    |--------------------------------------------------------------------------
    */

    private function calculateLexicalScore(
        KnowledgeDocument $document,
        string $normalizedQuestion,
        array $keywords
    ): int {
        $title =
            $this->normalize(
                $document->title
            );

        $content =
            $this->normalize(
                $document->content
            );

        $score = 0;

        if (
            $normalizedQuestion !== ''
            &&
            Str::contains(
                $title,
                $normalizedQuestion
            )
        ) {
            $score += 30;
        }

        if (
            $normalizedQuestion !== ''
            &&
            Str::contains(
                $content,
                $normalizedQuestion
            )
        ) {
            $score += 15;
        }

        foreach ($keywords as $keyword) {
            /*
             * Keyword xuất hiện trong title
             * có giá trị cao hơn content.
             */
            if (
                Str::contains(
                    $title,
                    $keyword
                )
            ) {
                $score += 8;
            }

            if (
                Str::contains(
                    $content,
                    $keyword
                )
            ) {
                $score += 3;
            }
        }

        /*
         * Giá / chi phí:
         * ưu tiên SERVICE.
         */
        if (
            $document->source_type
            === 'SERVICE'
            &&
            $this->containsAny(
                $normalizedQuestion,
                [
                    'gia',
                    'bao nhieu',
                    'chi phi',
                    'tien',
                ]
            )
        ) {
            $score += 8;
        }

        /*
         * Quy trình:
         * ưu tiên FAQ.
         */
        if (
            $document->source_type
            === 'FAQ'
            &&
            $this->containsAny(
                $normalizedQuestion,
                [
                    'quy trinh',
                    'lam sao',
                    'nhu the nao',
                    'cach',
                    'dat lich',
                    'thanh toan',
                ]
            )
        ) {
            $score += 6;
        }

        return $score;
    }


    /*
    |--------------------------------------------------------------------------
    | COSINE SIMILARITY
    |--------------------------------------------------------------------------
    */

    private function cosineSimilarity(
        array $vectorA,
        mixed $vectorB
    ): ?float {
        if (
            !is_array($vectorB)
            ||
            empty($vectorA)
            ||
            empty($vectorB)
        ) {
            return null;
        }

        if (
            count($vectorA)
            !== count($vectorB)
        ) {
            return null;
        }

        $dotProduct = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        foreach (
            $vectorA
            as $index => $valueA
        ) {
            $valueB =
                $vectorB[$index]
                ?? null;

            if (
                !is_numeric($valueA)
                ||
                !is_numeric($valueB)
            ) {
                return null;
            }

            $a =
                (float) $valueA;

            $b =
                (float) $valueB;

            $dotProduct +=
                $a * $b;

            $normA +=
                $a * $a;

            $normB +=
                $b * $b;
        }

        if (
            $normA <= 0
            ||
            $normB <= 0
        ) {
            return null;
        }

        return
            $dotProduct
            /
            (
                sqrt($normA)
                *
                sqrt($normB)
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RAG CONTEXT
    |--------------------------------------------------------------------------
    */

    public function buildContext(
        Collection $documents
    ): string {
        if ($documents->isEmpty()) {
            return '';
        }

        return $documents
            ->values()
            ->map(
                function (
                    KnowledgeDocument $document,
                    int $index
                ) {
                    $number =
                        $index + 1;

                    return implode(
                        "\n",
                        [
                            "[TÀI LIỆU {$number}]",
                            "Tiêu đề: {$document->title}",
                            "Loại nguồn: {$document->source_type}",
                            "Nội dung: {$document->content}",
                        ]
                    );
                }
            )
            ->implode(
                "\n\n"
            );
    }


    public function buildSources(
        Collection $documents
    ): array {
        return $documents
            ->map(
                function (
                    KnowledgeDocument $document
                ) {
                    $semanticScore =
                        $document
                            ->semantic_score
                        ?? null;

                    $hybridScore =
                        $document
                            ->hybrid_score
                        ?? null;

                    return [
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

                        'retrieval_mode' =>
                            $document
                                ->retrieval_mode
                            ?? 'lexical',

                        'semantic_score' =>
                            $semanticScore !== null
                                ? round(
                                    (float)
                                    $semanticScore,
                                    4
                                )
                                : null,

                        'lexical_score' =>
                            (int)
                            (
                                $document
                                    ->lexical_score
                                ?? 0
                            ),

                        'hybrid_score' =>
                            $hybridScore !== null
                                ? round(
                                    (float)
                                    $hybridScore,
                                    4
                                )
                                : null,
                    ];
                }
            )
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | STRING HELPERS
    |--------------------------------------------------------------------------
    */

    private function normalize(
        string $text
    ): string {
        $text =
            Str::lower(
                trim($text)
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
                $text ?? ''
            )
            ?? ''
        );
    }


    private function extractKeywords(
        string $question
    ): array {
        $words =
            preg_split(
                '/\s+/u',
                $question,
                -1,
                PREG_SPLIT_NO_EMPTY
            )
            ?: [];

        /*
         * Loại thêm các từ rất chung.
         *
         * "xe" trước đây là nguyên nhân
         * khiến nhiều service ô tô không
         * liên quan vẫn nhận lexical score.
         */
        $stopWords = [
            'la',
            'va',
            'cua',
            'cho',
            'toi',
            'minh',
            'ban',
            'co',
            'khong',
            'duoc',
            'nhu',
            'the',
            'nao',
            'bao',
            've',
            'voi',
            'o',
            'trong',
            'mot',
            'nhung',
            'cac',
            'thi',
            'nen',
            'can',
            'muon',
            'hoi',
            'giup',
            'xin',
            'xe',
            'oto',
            'o',
            'to',
            'lau',
            'roi',
            'phan',
            'xem',
            'lai',
        ];

        return collect(
            $words
        )
            ->filter(
                fn ($word) =>
                    strlen($word) >= 2
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


    private function containsAny(
        string $text,
        array $phrases
    ): bool {
        foreach ($phrases as $phrase) {
            if (
                Str::contains(
                    $text,
                    $this->normalize(
                        $phrase
                    )
                )
            ) {
                return true;
            }
        }

        return false;
    }
}