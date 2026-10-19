<?php

namespace App\Services;

use App\Models\KnowledgeDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class KnowledgeRetrievalService
{
    public function __construct(
        private GeminiEmbeddingService $embeddingService
    ) {
    }


    /**
     * Hybrid Retrieval:
     *
     * Query
     * + Brand Detection
     * + Semantic Similarity
     * + Exact-token Lexical Match
     * + Keyword Coverage
     * + Brand-aware Filtering
     * + General Technical Fallback
     * + Relevance Gate
     */
    public function retrieve(
        string $question,
        ?int $limit = null
    ): Collection {
        $question =
            trim(
                $question
            );


        if ($question === '') {
            return collect();
        }


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


        /*
        |--------------------------------------------------------------------------
        | QUESTION ANALYSIS
        |--------------------------------------------------------------------------
        */

        $normalizedQuestion =
            $this->normalize(
                $question
            );


        $mentionedBrand =
            $this->detectMentionedBrand(
                $question
            );


        /*
         * Nếu câu hỏi có hãng:
         *
         * BMW xe bị rung
         *
         * thì "BMW" là context,
         * còn "rung" mới là vấn đề kỹ thuật.
         *
         * Không bắt GENERAL_GUIDE phải chứa chữ BMW.
         */
        $topicQuestion =
            $this->removeBrandFromQuestion(
                $normalizedQuestion,
                $mentionedBrand
            );


        $keywords =
            $this->extractKeywords(
                $topicQuestion
            );


        /*
         * Trong trường hợp câu hỏi gần như
         * chỉ có tên hãng, dùng lại query gốc.
         */
        if (empty($keywords)) {
            $keywords =
                $this->extractKeywords(
                    $normalizedQuestion
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DOCUMENT ANALYSIS
        |--------------------------------------------------------------------------
        */

        $documents =
            $documents->map(
                function (
                    KnowledgeDocument $document
                ) use (
                    $topicQuestion,
                    $keywords,
                    $mentionedBrand
                ) {
                    $metadata =
                        is_array(
                            $document->metadata
                        )
                            ? $document->metadata
                            : [];


                    $documentBrand =
                        isset(
                            $metadata['brand']
                        )
                        &&
                        is_string(
                            $metadata['brand']
                        )
                            ? trim(
                                $metadata['brand']
                            )
                            : null;


                    $documentModel =
                        isset(
                            $metadata['model']
                        )
                        &&
                        is_string(
                            $metadata['model']
                        )
                            ? trim(
                                $metadata['model']
                            )
                            : null;


                    $documentScope =
                        isset(
                            $metadata['scope']
                        )
                        &&
                        is_string(
                            $metadata['scope']
                        )
                            ? strtoupper(
                                trim(
                                    $metadata['scope']
                                )
                            )
                            : null;


                    /*
                     * Có model thì về logic
                     * được xem là MODEL_SPECIFIC.
                     */
                    if (
                        filled(
                            $documentModel
                        )
                    ) {
                        $documentScope =
                            'MODEL_SPECIFIC';
                    }


                    $brandMatch =
                        $this->isBrandMatch(
                            $mentionedBrand,
                            $documentBrand
                        );


                    /*
                     * Khi user nói rõ Toyota,
                     * document Honda/Mazda/... phải bị loại.
                     */
                    $brandConflict =
                        $this->isBrandConflict(
                            $mentionedBrand,
                            $documentBrand
                        );


                    $analysis =
                        $this
                            ->calculateLexicalAnalysis(
                                $document,
                                $topicQuestion,
                                $keywords
                            );


                    $lexicalScore =
                        $analysis[
                            'score'
                        ];


                    /*
                     * Ưu tiên tài liệu đúng hãng,
                     * nhưng bonus này KHÔNG được dùng
                     * để biến tài liệu không liên quan
                     * thành tài liệu liên quan.
                     *
                     * Relevance Gate vẫn kiểm tra
                     * keyword evidence riêng.
                     */
                    if ($brandMatch) {
                        $lexicalScore +=
                            8;
                    }


                    $document->lexical_score =
                        $lexicalScore;


                    $document->matched_keyword_count =
                        $analysis[
                            'matched_keyword_count'
                        ];


                    $document->query_keyword_count =
                        count(
                            $keywords
                        );


                    $document->keyword_coverage =
                        $analysis[
                            'keyword_coverage'
                        ];


                    $document->semantic_score =
                        null;


                    $document->hybrid_score =
                        null;


                    $document->ranking_score =
                        null;


                    $document->retrieval_mode =
                        'lexical';


                    $document->mentioned_brand =
                        $mentionedBrand[
                            'name'
                        ]
                        ?? null;


                    $document->document_brand =
                        $documentBrand;


                    $document->document_model =
                        $documentModel;


                    $document->document_scope =
                        $documentScope;


                    $document->brand_match =
                        $brandMatch;


                    $document->brand_conflict =
                        $brandConflict;


                    return $document;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | REMOVE WRONG BRAND DOCUMENTS
        |--------------------------------------------------------------------------
        |
        | Ví dụ:
        |
        | Query:
        | Honda bao lâu bảo dưỡng?
        |
        | Không cho:
        | Toyota 5.000 km / 6 tháng
        | Mazda 5.000 km / 3 tháng
        |
        | GENERAL_GUIDE vẫn được phép tham gia.
        |
        */

        $documents =
            $documents
                ->reject(
                    fn (
                        KnowledgeDocument $document
                    ) =>
                        (bool) (
                            $document
                                ->brand_conflict
                            ?? false
                        )
                )
                ->values();


        if ($documents->isEmpty()) {
            return collect();
        }


        /*
        |--------------------------------------------------------------------------
        | EMBEDDING AVAILABILITY
        |--------------------------------------------------------------------------
        */

        $aiMode =
            strtolower(
                trim(
                    (string) config(
                        'ai.mode',
                        'live'
                    )
                )
            );


        /*
         * TEST mode tuyệt đối không gọi
         * Gemini Embedding API thật.
         *
         * Toàn bộ retrieval sẽ dùng
         * lexical ranking tại local.
         */
        if ($aiMode === 'test') {
            logger()->info(
                'Knowledge retrieval is using lexical-only mode because AI_MODE=test.'
            );

            return $this
                ->rankLexically(
                    $documents,
                    $limit
                );
        }


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


        /*
        |--------------------------------------------------------------------------
        | HYBRID SEARCH
        |--------------------------------------------------------------------------
        */

        try {
            /*
             * Semantic embedding vẫn sử dụng
             * câu hỏi đầy đủ để giữ ngữ cảnh hãng.
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
            report(
                $exception
            );


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
        $semanticWeight =
            max(
                0,
                (float) config(
                    'ai.rag.semantic_weight',
                    0.80
                )
            );


        $lexicalWeight =
            max(
                0,
                (float) config(
                    'ai.rag.lexical_weight',
                    0.20
                )
            );


        $weightTotal =
            $semanticWeight
            +
            $lexicalWeight;


        if ($weightTotal <= 0) {
            $semanticWeight =
                0.80;

            $lexicalWeight =
                0.20;
        } else {
            $semanticWeight =
                $semanticWeight
                /
                $weightTotal;

            $lexicalWeight =
                $lexicalWeight
                /
                $weightTotal;
        }


        $semanticMinScore =
            (float) config(
                'ai.rag.semantic_min_score',
                0.55
            );


        $semanticStrongScore =
            (float) config(
                'ai.rag.semantic_strong_score',
                0.78
            );


        $lexicalMinScore =
            (int) config(
                'ai.rag.lexical_min_score',
                8
            );


        $lexicalStrongScore =
            (int) config(
                'ai.rag.lexical_strong_score',
                16
            );


        $hybridMinScore =
            (float) config(
                'ai.rag.hybrid_min_score',
                0.50
            );


        /*
        |--------------------------------------------------------------------------
        | SEMANTIC SCORE
        |--------------------------------------------------------------------------
        */

        $documents =
            $documents->map(
                function (
                    KnowledgeDocument $document
                ) use (
                    $queryEmbedding
                ) {
                    $document->semantic_score =
                        $this
                            ->cosineSimilarity(
                                $queryEmbedding,
                                $document->embedding
                            );


                    return $document;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE LEXICAL SCORE
        |--------------------------------------------------------------------------
        */

        $maxLexicalScore =
            (float)
            $documents->max(
                'lexical_score'
            );


        if ($maxLexicalScore <= 0) {
            $maxLexicalScore =
                1;
        }


        /*
        |--------------------------------------------------------------------------
        | HYBRID + RANKING SCORE
        |--------------------------------------------------------------------------
        */

        $documents =
            $documents->map(
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


                    /*
                     * ranking_score chỉ dùng để sắp xếp.
                     *
                     * Relevance Gate sử dụng
                     * hybrid_score chưa bonus.
                     */
                    $rankingScore =
                        $hybridScore;


                    if (
                        (bool) (
                            $document
                                ->brand_match
                            ?? false
                        )
                    ) {
                        /*
                         * Tài liệu đúng hãng được ưu tiên.
                         */
                        $rankingScore +=
                            0.12;
                    }


                    if (
                        (
                            $document
                                ->document_scope
                            ?? null
                        )
                        ===
                        'MODEL_SPECIFIC'
                    ) {
                        $rankingScore +=
                            0.03;
                    }


                    $document->ranking_score =
                        $rankingScore;


                    $document->retrieval_mode =
                        'hybrid';


                    return $document;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | RELEVANCE GATE
        |--------------------------------------------------------------------------
        */

        $ranked =
            $documents
                ->filter(
                    function (
                        KnowledgeDocument $document
                    ) use (
                        $semanticMinScore,
                        $semanticStrongScore,
                        $lexicalMinScore,
                        $lexicalStrongScore,
                        $hybridMinScore
                    ) {
                        $semanticScore =
                            $document
                                ->semantic_score;


                        $lexicalScore =
                            (int)
                            (
                                $document
                                    ->lexical_score
                                ?? 0
                            );


                        $hybridScore =
                            (float)
                            (
                                $document
                                    ->hybrid_score
                                ?? 0
                            );


                        $matchedKeywordCount =
                            (int)
                            (
                                $document
                                    ->matched_keyword_count
                                ?? 0
                            );


                        $queryKeywordCount =
                            (int)
                            (
                                $document
                                    ->query_keyword_count
                                ?? 0
                            );


                        $keywordCoverage =
                            (float)
                            (
                                $document
                                    ->keyword_coverage
                                ?? 0
                            );


                        /*
                         * Nếu không còn keyword kỹ thuật,
                         * retrieval không đủ cơ sở.
                         */
                        if ($queryKeywordCount <= 0) {
                            return false;
                        }


                        /*
                         * Query có >= 2 keyword:
                         * cần ít nhất 2 keyword khác nhau.
                         *
                         * Query chỉ còn 1 keyword:
                         * cho phép 1 match.
                         *
                         * Điều này giúp:
                         *
                         * "BMW xe bị rung"
                         *
                         * sau khi bỏ BMW + stop words
                         * còn:
                         *
                         * "rung"
                         *
                         * GENERAL_GUIDE về rung vẫn dùng được.
                         */
                        if ($queryKeywordCount >= 2) {
                            $hasEnoughKeywordEvidence =
                                $matchedKeywordCount
                                >= 2;
                        } else {
                            $hasEnoughKeywordEvidence =
                                $matchedKeywordCount
                                >= 1;
                        }


                        /*
                         * Query dài nhưng chỉ match
                         * một phần nhỏ thì loại.
                         */
                        if (
                            $queryKeywordCount >= 3
                            &&
                            $keywordCoverage < 0.40
                        ) {
                            return false;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CASE 1 - STRONG LEXICAL
                        |--------------------------------------------------------------------------
                        */

                        $strongLexicalEvidence =
                            $hasEnoughKeywordEvidence
                            &&
                            $lexicalScore
                            >=
                            $lexicalStrongScore;


                        /*
                        |--------------------------------------------------------------------------
                        | CASE 2 - HYBRID CONSENSUS
                        |--------------------------------------------------------------------------
                        */

                        $hybridEvidence =
                            $hasEnoughKeywordEvidence
                            &&
                            $lexicalScore
                            >=
                            $lexicalMinScore
                            &&
                            (
                                $semanticScore
                                === null
                                ||
                                $semanticScore
                                >=
                                $semanticMinScore
                            )
                            &&
                            $hybridScore
                            >=
                            $hybridMinScore;


                        /*
                        |--------------------------------------------------------------------------
                        | CASE 3 - VERY STRONG SEMANTIC
                        |--------------------------------------------------------------------------
                        |
                        | Semantic cao vẫn cần keyword evidence.
                        |
                        */

                        $strongSemanticEvidence =
                            $hasEnoughKeywordEvidence
                            &&
                            $semanticScore
                            !== null
                            &&
                            $semanticScore
                            >=
                            $semanticStrongScore;


                        return
                            $strongLexicalEvidence
                            ||
                            $hybridEvidence
                            ||
                            $strongSemanticEvidence;
                    }
                )
                ->sort(
                    function (
                        KnowledgeDocument $a,
                        KnowledgeDocument $b
                    ) {
                        /*
                         * ranking_score đã chứa
                         * brand bonus.
                         */
                        $rankingCompare =
                            (
                                $b
                                    ->ranking_score
                                ?? 0
                            )
                            <=>
                            (
                                $a
                                    ->ranking_score
                                ?? 0
                            );


                        if ($rankingCompare !== 0) {
                            return $rankingCompare;
                        }


                        $coverageCompare =
                            (
                                $b
                                    ->keyword_coverage
                                ?? 0
                            )
                            <=>
                            (
                                $a
                                    ->keyword_coverage
                                ?? 0
                            );


                        if ($coverageCompare !== 0) {
                            return $coverageCompare;
                        }


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
        $lexicalMinScore =
            (int) config(
                'ai.rag.lexical_min_score',
                8
            );


        return $documents
            ->filter(
                function (
                    KnowledgeDocument $document
                ) use (
                    $lexicalMinScore
                ) {
                    $lexicalScore =
                        (int)
                        (
                            $document
                                ->lexical_score
                            ?? 0
                        );


                    $matchedKeywordCount =
                        (int)
                        (
                            $document
                                ->matched_keyword_count
                            ?? 0
                        );


                    $queryKeywordCount =
                        (int)
                        (
                            $document
                                ->query_keyword_count
                            ?? 0
                        );


                    if ($queryKeywordCount <= 0) {
                        return false;
                    }


                    if ($queryKeywordCount >= 2) {
                        $hasEnoughKeywordEvidence =
                            $matchedKeywordCount
                            >= 2;
                    } else {
                        $hasEnoughKeywordEvidence =
                            $matchedKeywordCount
                            >= 1;
                    }


                    return
                        $hasEnoughKeywordEvidence
                        &&
                        $lexicalScore
                        >=
                        $lexicalMinScore;
                }
            )
            ->sort(
                function (
                    KnowledgeDocument $a,
                    KnowledgeDocument $b
                ) {
                    /*
                     * Nếu không có embedding,
                     * tài liệu đúng hãng vẫn ưu tiên.
                     */
                    $brandCompare =
                        (
                            (bool) (
                                $b
                                    ->brand_match
                                ?? false
                            )
                        )
                        <=>
                        (
                            (bool) (
                                $a
                                    ->brand_match
                                ?? false
                            )
                        );


                    if ($brandCompare !== 0) {
                        return $brandCompare;
                    }


                    return
                        (
                            $b
                                ->lexical_score
                            ?? 0
                        )
                        <=>
                        (
                            $a
                                ->lexical_score
                            ?? 0
                        );
                }
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


                    $document->ranking_score =
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
    | LEXICAL ANALYSIS
    |--------------------------------------------------------------------------
    */

    private function calculateLexicalAnalysis(
        KnowledgeDocument $document,
        string $normalizedQuestion,
        array $keywords
    ): array {
        $title =
            $this->normalize(
                $document->title
            );


        $content =
            $this->normalize(
                $document->content
            );


        $titleTokens =
            $this->tokenize(
                $title
            );


        $contentTokens =
            $this->tokenize(
                $content
            );


        $metadataTokens =
            $this->metadataTokens(
                $document
            );


        $score =
            0;


        $matchedKeywords =
            [];


        /*
        |--------------------------------------------------------------------------
        | FULL PHRASE MATCH
        |--------------------------------------------------------------------------
        */

        if (
            count(
                $keywords
            )
            >= 2
        ) {
            if (
                $normalizedQuestion !== ''
                &&
                $this->containsPhrase(
                    $title,
                    $normalizedQuestion
                )
            ) {
                $score +=
                    30;
            }


            if (
                $normalizedQuestion !== ''
                &&
                $this->containsPhrase(
                    $content,
                    $normalizedQuestion
                )
            ) {
                $score +=
                    15;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | EXACT TOKEN MATCH
        |--------------------------------------------------------------------------
        */

        foreach (
            $keywords
            as $keyword
        ) {
            $matched =
                false;


            /*
             * Title quan trọng nhất.
             */
            if (
                in_array(
                    $keyword,
                    $titleTokens,
                    true
                )
            ) {
                $score +=
                    8;

                $matched =
                    true;
            }


            /*
             * Metadata:
             * topic / brand / model / keywords.
             */
            if (
                in_array(
                    $keyword,
                    $metadataTokens,
                    true
                )
            ) {
                $score +=
                    6;

                $matched =
                    true;
            }


            /*
             * Content.
             */
            if (
                in_array(
                    $keyword,
                    $contentTokens,
                    true
                )
            ) {
                $score +=
                    3;

                $matched =
                    true;
            }


            if ($matched) {
                $matchedKeywords[] =
                    $keyword;
            }
        }


        $matchedKeywords =
            array_values(
                array_unique(
                    $matchedKeywords
                )
            );


        $keywordCount =
            count(
                $keywords
            );


        $matchedKeywordCount =
            count(
                $matchedKeywords
            );


        $keywordCoverage =
            $keywordCount > 0
                ? $matchedKeywordCount
                    /
                    $keywordCount
                : 0;


        return [
            'score' =>
                $score,

            'matched_keyword_count' =>
                $matchedKeywordCount,

            'keyword_coverage' =>
                $keywordCoverage,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | METADATA TOKENS
    |--------------------------------------------------------------------------
    */

    private function metadataTokens(
        KnowledgeDocument $document
    ): array {
        $metadata =
            is_array(
                $document->metadata
            )
                ? $document->metadata
                : [];


        $parts =
            [];


        foreach (
            [
                'topic',
                'brand',
                'model',
                'scope',
            ]
            as $key
        ) {
            $value =
                $metadata[
                    $key
                ]
                ?? null;


            if (
                is_string(
                    $value
                )
                &&
                trim(
                    $value
                )
                !== ''
            ) {
                $parts[] =
                    $value;
            }
        }


        $metadataKeywords =
            $metadata[
                'keywords'
            ]
            ?? [];


        if (
            is_array(
                $metadataKeywords
            )
        ) {
            foreach (
                $metadataKeywords
                as $keyword
            ) {
                if (
                    is_string(
                        $keyword
                    )
                    &&
                    trim(
                        $keyword
                    )
                    !== ''
                ) {
                    $parts[] =
                        $keyword;
                }
            }
        }


        return $this->tokenize(
            $this->normalize(
                implode(
                    ' ',
                    $parts
                )
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BRAND DETECTION
    |--------------------------------------------------------------------------
    */

    private function detectMentionedBrand(
        string $question
    ): ?array {
        $normalizedQuestion =
            $this->normalizeForBrand(
                $question
            );


        $brands =
            config(
                'knowledge_sources.brands',
                []
            );


        if (!is_array($brands)) {
            return null;
        }


        foreach (
            $brands
            as $code =>
                $brand
        ) {
            if (!is_array($brand)) {
                continue;
            }


            $name =
                trim(
                    (string) (
                        $brand['name']
                        ?? ''
                    )
                );


            if ($name === '') {
                continue;
            }


            $aliases =
                $this->brandAliases(
                    $name
                );


            foreach (
                $aliases
                as $alias
            ) {
                if (
                    $this->containsPhrase(
                        $normalizedQuestion,
                        $this->normalizeForBrand(
                            $alias
                        )
                    )
                ) {
                    return [
                        'code' =>
                            (string) $code,

                        'name' =>
                            $name,

                        'matched_alias' =>
                            $alias,
                    ];
                }
            }
        }


        return null;
    }


    private function brandAliases(
        string $brandName
    ): array {
        $aliases = [
            $brandName,
        ];


        $normalized =
            $this->normalizeForBrand(
                $brandName
            );


        if (
            $normalized
            ===
            'mercedes benz'
        ) {
            $aliases[] =
                'Mercedes';

            $aliases[] =
                'Benz';
        }


        if (
            $normalized
            ===
            'vinfast'
        ) {
            $aliases[] =
                'Vin Fast';
        }


        return array_values(
            array_unique(
                $aliases
            )
        );
    }


    private function removeBrandFromQuestion(
        string $normalizedQuestion,
        ?array $mentionedBrand
    ): string {
        if (
            !$mentionedBrand
            ||
            empty(
                $mentionedBrand['name']
            )
        ) {
            return $normalizedQuestion;
        }


        $aliases =
            $this->brandAliases(
                (string)
                $mentionedBrand[
                    'name'
                ]
            );


        $result =
            ' '
            . $normalizedQuestion
            . ' ';


        foreach (
            $aliases
            as $alias
        ) {
            $normalizedAlias =
                $this->normalize(
                    $alias
                );


            if ($normalizedAlias === '') {
                continue;
            }


            $result =
                str_replace(
                    ' '
                    . $normalizedAlias
                    . ' ',
                    ' ',
                    $result
                );
        }


        return trim(
            preg_replace(
                '/\s+/u',
                ' ',
                $result
            )
            ?? ''
        );
    }


    private function isBrandMatch(
        ?array $mentionedBrand,
        ?string $documentBrand
    ): bool {
        if (
            !$mentionedBrand
            ||
            blank(
                $documentBrand
            )
        ) {
            return false;
        }


        return
            $this->normalizeForBrand(
                (string)
                $mentionedBrand[
                    'name'
                ]
            )
            ===
            $this->normalizeForBrand(
                $documentBrand
            );
    }


    private function isBrandConflict(
        ?array $mentionedBrand,
        ?string $documentBrand
    ): bool {
        if (
            !$mentionedBrand
            ||
            blank(
                $documentBrand
            )
        ) {
            return false;
        }


        return
            !$this->isBrandMatch(
                $mentionedBrand,
                $documentBrand
            );
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
            !is_array(
                $vectorB
            )
            ||
            empty(
                $vectorA
            )
            ||
            empty(
                $vectorB
            )
        ) {
            return null;
        }


        if (
            count(
                $vectorA
            )
            !==
            count(
                $vectorB
            )
        ) {
            return null;
        }


        $dotProduct =
            0.0;


        $normA =
            0.0;


        $normB =
            0.0;


        foreach (
            $vectorA
            as $index =>
                $valueA
        ) {
            $valueB =
                $vectorB[
                    $index
                ]
                ?? null;


            if (
                !is_numeric(
                    $valueA
                )
                ||
                !is_numeric(
                    $valueB
                )
            ) {
                return null;
            }


            $a =
                (float)
                $valueA;


            $b =
                (float)
                $valueB;


            $dotProduct +=
                $a
                *
                $b;


            $normA +=
                $a
                *
                $a;


            $normB +=
                $b
                *
                $b;
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
                sqrt(
                    $normA
                )
                *
                sqrt(
                    $normB
                )
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
                        $index
                        +
                        1;


                    $metadata =
                        is_array(
                            $document->metadata
                        )
                            ? $document->metadata
                            : [];


                    $lines = [
                        "[TÀI LIỆU {$number}]",
                        "Tiêu đề: {$document->title}",
                        "Loại nguồn: {$document->source_type}",
                    ];


                    if (
                        filled(
                            $metadata['scope']
                            ?? null
                        )
                    ) {
                        $lines[] =
                            'Phạm vi: '
                            . $metadata[
                                'scope'
                            ];
                    }


                    if (
                        filled(
                            $metadata['brand']
                            ?? null
                        )
                    ) {
                        $lines[] =
                            'Hãng: '
                            . $metadata[
                                'brand'
                            ];
                    }


                    if (
                        filled(
                            $metadata['model']
                            ?? null
                        )
                    ) {
                        $lines[] =
                            'Dòng xe: '
                            . $metadata[
                                'model'
                            ];
                    }


                    if (
                        filled(
                            $metadata['source_name']
                            ?? null
                        )
                    ) {
                        $lines[] =
                            'Nguồn: '
                            . $metadata[
                                'source_name'
                            ];
                    }


                    if (
                        filled(
                            $metadata['source_title']
                            ?? null
                        )
                    ) {
                        $lines[] =
                            'Tài liệu nguồn: '
                            . $metadata[
                                'source_title'
                            ];
                    }


                    if (
                        filled(
                            $metadata['source_url']
                            ?? null
                        )
                    ) {
                        $lines[] =
                            'URL nguồn: '
                            . $metadata[
                                'source_url'
                            ];
                    }


                    $lines[] =
                        'Nội dung: '
                        . $document->content;


                    return implode(
                        "\n",
                        $lines
                    );
                }
            )
            ->implode(
                "\n\n"
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RAG SOURCES
    |--------------------------------------------------------------------------
    */

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


                    $rankingScore =
                        $document
                            ->ranking_score
                        ?? null;


                    $metadata =
                        is_array(
                            $document->metadata
                        )
                            ? $document->metadata
                            : [];


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

                        'topic' =>
                            $metadata[
                                'topic'
                            ]
                            ?? null,

                        'scope' =>
                            $metadata[
                                'scope'
                            ]
                            ?? null,

                        'brand' =>
                            $metadata[
                                'brand'
                            ]
                            ?? null,

                        'model' =>
                            $metadata[
                                'model'
                            ]
                            ?? null,

                        'source_name' =>
                            $metadata[
                                'source_name'
                            ]
                            ?? null,

                        'source_title' =>
                            $metadata[
                                'source_title'
                            ]
                            ?? null,

                        'source_url' =>
                            $metadata[
                                'source_url'
                            ]
                            ?? null,

                        'retrieval_mode' =>
                            $document
                                ->retrieval_mode
                            ?? 'lexical',

                        'brand_match' =>
                            (bool) (
                                $document
                                    ->brand_match
                                ?? false
                            ),

                        'semantic_score' =>
                            $semanticScore
                            !== null
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

                        'matched_keyword_count' =>
                            (int)
                            (
                                $document
                                    ->matched_keyword_count
                                ?? 0
                            ),

                        'query_keyword_count' =>
                            (int)
                            (
                                $document
                                    ->query_keyword_count
                                ?? 0
                            ),

                        'keyword_coverage' =>
                            round(
                                (float)
                                (
                                    $document
                                        ->keyword_coverage
                                    ?? 0
                                ),
                                4
                            ),

                        'hybrid_score' =>
                            $hybridScore
                            !== null
                                ? round(
                                    (float)
                                    $hybridScore,
                                    4
                                )
                                : null,

                        'ranking_score' =>
                            $rankingScore
                            !== null
                                ? round(
                                    (float)
                                    $rankingScore,
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
    | NORMALIZATION
    |--------------------------------------------------------------------------
    |
    | Không bỏ dấu tiếng Việt cho lexical search.
    |
    | Điều này tránh:
    |
    | "dầu" → "dau"
    | "đâu" → "dau"
    |
    | hoặc:
    |
    | "bảo" → "bao"
    | "bao"  → "bao"
    |
    | gây false positive.
    |
    */

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
            preg_replace(
                '/[^\p{L}\p{N}]+/u',
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


    /*
    |--------------------------------------------------------------------------
    | ASCII NORMALIZATION FOR BRAND NAMES
    |--------------------------------------------------------------------------
    */

    private function normalizeForBrand(
        string $text
    ): string {
        return Str::ascii(
            $this->normalize(
                $text
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TOKENIZE
    |--------------------------------------------------------------------------
    */

    private function tokenize(
        string $text
    ): array {
        if (
            trim(
                $text
            )
            === ''
        ) {
            return [];
        }


        return collect(
            preg_split(
                '/\s+/u',
                $text,
                -1,
                PREG_SPLIT_NO_EMPTY
            )
            ?: []
        )
            ->unique()
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | KEYWORD EXTRACTION
    |--------------------------------------------------------------------------
    */

    private function extractKeywords(
        string $question
    ): array {
        $words =
            $this->tokenize(
                $question
            );


        /*
         * Giữ dấu tiếng Việt nên:
         *
         * bảo != bao
         * dầu != đâu
         *
         * giúp lexical search chính xác hơn.
         */
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
            'lâu',
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
            'xin',

            /*
             * Vehicle words quá chung.
             */
            'xe',
            'ô',
            'tô',
            'oto',

            /*
             * Conversation words.
             */
            'phần',
            'xem',
            'lại',
            'này',
            'kia',
            'đó',
            'đây',
            'hôm',
            'nhiều',
            'mấy',
            'gì',
            'sao',
            'cách',
            'làm',
            'tốt',
            'để',

            /*
             * Generic state/action words.
             */
            'bị',
            'phải',
            'xử',
            'lý',
            'khi',
            'kiểm',
            'tra',
            'hãng',

            /*
             * "máy" quá chung.
             * "nóng máy" vẫn còn keyword "nóng".
             */
            'máy',
        ];


        /*
         * Một số token 2 ký tự
         * vẫn có ý nghĩa kỹ thuật.
         */
        $allowedShortKeywords = [
            'vf',
            'ev',
        ];


        return collect(
            $words
        )
            ->filter(
                function (
                    string $word
                ) use (
                    $stopWords,
                    $allowedShortKeywords
                ) {
                    if (
                        in_array(
                            $word,
                            $stopWords,
                            true
                        )
                    ) {
                        return false;
                    }


                    if (
                        ctype_digit(
                            $word
                        )
                    ) {
                        return true;
                    }


                    if (
                        in_array(
                            $word,
                            $allowedShortKeywords,
                            true
                        )
                    ) {
                        return true;
                    }


                    return
                        mb_strlen(
                            $word
                        )
                        >= 3;
                }
            )
            ->unique()
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | PHRASE MATCH
    |--------------------------------------------------------------------------
    */

    private function containsPhrase(
        string $haystack,
        string $needle
    ): bool {
        $haystack =
            trim(
                $haystack
            );


        $needle =
            trim(
                $needle
            );


        if (
            $haystack === ''
            ||
            $needle === ''
        ) {
            return false;
        }


        return str_contains(
            ' '
            . $haystack
            . ' ',
            ' '
            . $needle
            . ' '
        );
    }
}