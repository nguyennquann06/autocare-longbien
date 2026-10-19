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
    | AI Runtime Mode
    |--------------------------------------------------------------------------
    |
    | live           = Retrieval + Gemini
    | test           = Không gọi API Gemini thật
    | retrieval_only = Chỉ trả lời bằng nội dung truy xuất
    |
    */

    'mode' => env(
        'AI_MODE',
        'live'
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
    | Conversation Memory
    |--------------------------------------------------------------------------
    */

    'conversation' => [

        'history_message_limit' => (int) env(
            'AI_HISTORY_MESSAGE_LIMIT',
            8
        ),

        'routing_history_message_limit' => (int) env(
            'AI_ROUTING_HISTORY_MESSAGE_LIMIT',
            4
        ),

        'max_message_chars' => (int) env(
            'AI_HISTORY_MAX_MESSAGE_CHARS',
            1800
        ),

    ],


    /*
    |--------------------------------------------------------------------------
    | RAG Settings
    |--------------------------------------------------------------------------
    |
    | Retrieval không chỉ lấy tài liệu có điểm cao nhất.
    |
    | Một tài liệu chỉ được đưa vào prompt nếu vượt qua
    | Relevance Gate.
    |
    */

    'rag' => [

        'top_k' => (int) env(
            'AI_RAG_TOP_K',
            5
        ),

        'active_only' => true,


        /*
         * Trọng số Hybrid Search.
         */
        'semantic_weight' => (float) env(
            'AI_RAG_SEMANTIC_WEIGHT',
            0.80
        ),

        'lexical_weight' => (float) env(
            'AI_RAG_LEXICAL_WEIGHT',
            0.20
        ),


        /*
         * Semantic tối thiểu khi document
         * đồng thời có bằng chứng lexical.
         */
        'semantic_min_score' => (float) env(
            'AI_RAG_SEMANTIC_MIN_SCORE',
            0.55
        ),


        /*
         * Semantic cực mạnh.
         *
         * Vẫn yêu cầu có ít nhất một tín hiệu
         * lexical để hạn chế false positive.
         */
        'semantic_strong_score' => (float) env(
            'AI_RAG_SEMANTIC_STRONG_SCORE',
            0.78
        ),


        /*
         * Lexical score tối thiểu.
         */
        'lexical_min_score' => (int) env(
            'AI_RAG_LEXICAL_MIN_SCORE',
            8
        ),


        /*
         * Nếu lexical rất mạnh thì có thể
         * chấp nhận document ngay cả khi
         * semantic embedding không thật cao.
         */
        'lexical_strong_score' => (int) env(
            'AI_RAG_LEXICAL_STRONG_SCORE',
            16
        ),


        /*
         * Hybrid score tối thiểu.
         */
        'hybrid_min_score' => (float) env(
            'AI_RAG_HYBRID_MIN_SCORE',
            0.50
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
                15
            ),

            'connect_timeout' => (int) env(
                'GEMINI_CONNECT_TIMEOUT',
                5
            ),

            'retry_times' => (int) env(
                'GEMINI_RETRY_TIMES',
                2
            ),

            'daily_request_limit' => (int) env(
                'GEMINI_DAILY_REQUEST_LIMIT',
                100
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