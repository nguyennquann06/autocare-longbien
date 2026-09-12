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
    | Conversation Memory
    |--------------------------------------------------------------------------
    |
    | Chỉ giữ một lượng lịch sử ngắn
    | trong prompt để tránh tăng token
    | vô hạn khi conversation dài.
    |
    */

    'conversation' => [

        /*
         * Số message gần nhất
         * đưa trực tiếp vào LLM.
         *
         * 8 messages ~ khoảng 4 lượt hỏi đáp.
         */
        'history_message_limit' => (int) env(
            'AI_HISTORY_MESSAGE_LIMIT',
            8
        ),

        /*
         * Số message gần nhất dùng
         * hỗ trợ intent / pronoun resolution.
         */
        'routing_history_message_limit' => (int) env(
            'AI_ROUTING_HISTORY_MESSAGE_LIMIT',
            4
        ),

        /*
         * Giới hạn ký tự cho từng message
         * trước khi đưa vào prompt.
         */
        'max_message_chars' => (int) env(
            'AI_HISTORY_MAX_MESSAGE_CHARS',
            1800
        ),

    ],


    /*
    |--------------------------------------------------------------------------
    | RAG Settings
    |--------------------------------------------------------------------------
    */

    'rag' => [

        'top_k' => (int) env(
            'AI_RAG_TOP_K',
            5
        ),

        'active_only' => true,

        'semantic_weight' => (float) env(
            'AI_RAG_SEMANTIC_WEIGHT',
            0.75
        ),

        'lexical_weight' => (float) env(
            'AI_RAG_LEXICAL_WEIGHT',
            0.25
        ),

        'semantic_min_score' => (float) env(
            'AI_RAG_SEMANTIC_MIN_SCORE',
            0.35
        ),

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