<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    */

    'provider' => env(
        'AI_PROVIDER',
        'gemini'
    ),


    /*
    |--------------------------------------------------------------------------
    | Generation Settings
    |--------------------------------------------------------------------------
    */

    'max_output_tokens' => (int) env(
        'AI_MAX_OUTPUT_TOKENS',
        1200
    ),


    /*
    |--------------------------------------------------------------------------
    | RAG Settings
    |--------------------------------------------------------------------------
    */

    'rag' => [

        /*
         * Số tài liệu tối đa
         * đưa vào LLM context.
         */
        'top_k' => (int) env(
            'AI_RAG_TOP_K',
            5
        ),

        'active_only' => true,


        /*
         * Hybrid Retrieval
         *
         * Semantic giữ vai trò chính.
         * Lexical hỗ trợ các trường hợp:
         * - tên dịch vụ cụ thể
         * - từ khóa chính xác
         * - giá / mã / tên nghiệp vụ
         */
        'semantic_weight' => (float) env(
            'AI_RAG_SEMANTIC_WEIGHT',
            0.75
        ),

        'lexical_weight' => (float) env(
            'AI_RAG_LEXICAL_WEIGHT',
            0.25
        ),


        /*
         * Cosine similarity tối thiểu
         * để coi một document là
         * có liên quan về ngữ nghĩa.
         */
        'semantic_min_score' => (float) env(
            'AI_RAG_SEMANTIC_MIN_SCORE',
            0.35
        ),


        /*
         * Điểm hybrid tối thiểu.
         */
        'hybrid_min_score' => (float) env(
            'AI_RAG_HYBRID_MIN_SCORE',
            0.20
        ),

    ],


    /*
    |--------------------------------------------------------------------------
    | Embedding Settings
    |--------------------------------------------------------------------------
    */

    'embedding' => [

        'provider' => 'gemini',

        'model' => env(
            'GEMINI_EMBEDDING_MODEL',
            'gemini-embedding-2'
        ),

        'dimensions' => (int) env(
            'GEMINI_EMBEDDING_DIMENSIONS',
            768
        ),

        'timeout' => (int) env(
            'GEMINI_EMBEDDING_TIMEOUT',
            45
        ),

        'connect_timeout' => (int) env(
            'GEMINI_EMBEDDING_CONNECT_TIMEOUT',
            10
        ),

        'max_attempts' => (int) env(
            'GEMINI_EMBEDDING_MAX_ATTEMPTS',
            3
        ),

    ],


    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        'gemini' => [

            'api_key' => env(
                'GEMINI_API_KEY'
            ),

            'model' => env(
                'GEMINI_MODEL'
            ),

            'base_url' => env(
                'GEMINI_BASE_URL',
                'https://generativelanguage.googleapis.com/v1beta'
            ),

            'timeout' => (int) env(
                'GEMINI_TIMEOUT',
                45
            ),

            'connect_timeout' => (int) env(
                'GEMINI_CONNECT_TIMEOUT',
                10
            ),

        ],


        'openai' => [

            'api_key' => env(
                'OPENAI_API_KEY'
            ),

            'model' => env(
                'OPENAI_MODEL'
            ),

        ],

    ],

];