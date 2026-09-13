<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;

class ChatController extends Controller
{
    /**
     * Hiển thị giao diện chatbot.
     */
    public function index(
        Request $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | NEW CONVERSATION
        |--------------------------------------------------------------------------
        */

        if (
            $request->boolean(
                'new'
            )
        ) {
            $request
                ->session()
                ->forget(
                    'chat_conversation_id'
                );


            return view(
                'chat.index',
                [
                    'conversation' =>
                        null,

                    'messages' =>
                        collect(),
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT SESSION CONVERSATION
        |--------------------------------------------------------------------------
        */

        $conversation =
            $this->resolveConversation(
                $request,
                false
            );


        $messages =
            $conversation
                ? $conversation
                    ->messages()
                    ->orderBy(
                        'created_at'
                    )
                    ->orderBy(
                        'id'
                    )
                    ->get()
                : collect();


        return view(
            'chat.index',
            [
                'conversation' =>
                    $conversation,

                'messages' =>
                    $messages,
            ]
        );
    }


    /**
     * Nhận tin nhắn và trả
     * phản hồi AutoCare AI.
     */
    public function storeMessage(
        Request $request,
        ChatService $chatService
    ): JsonResponse {
        $validated =
            $request->validate([
                'message' => [
                    'required',
                    'string',
                    'max:2000',
                ],
            ]);


        $user =
            $request->user();


        $message =
            trim(
                $validated[
                    'message'
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | RESOLVE CURRENT CONVERSATION
        |--------------------------------------------------------------------------
        */

        $conversation =
            $this->resolveConversation(
                $request,
                true
            );


        /*
        |--------------------------------------------------------------------------
        | USER MESSAGE
        |--------------------------------------------------------------------------
        */

        $userMessage =
            $conversation
                ->messages()
                ->create([
                    'role' =>
                        ChatMessage::ROLE_USER,

                    'content' =>
                        $message,

                    'metadata' =>
                        null,

                    'token_count' =>
                        null,
                ]);


        /*
        |--------------------------------------------------------------------------
        | ASSISTANT RESPONSE
        |--------------------------------------------------------------------------
        */

        try {
            $result =
                $chatService->reply(
                    $message,
                    $user,
                    $conversation,
                    $userMessage->id
                );
        } catch (Throwable $exception) {
            report($exception);


            $result = [
                'content' =>
                    implode(
                        ' ',
                        [
                            'AutoCare AI đang gặp sự cố khi xử lý câu hỏi.',
                            'Vui lòng thử lại sau ít phút.',
                        ]
                    ),

                'sources' =>
                    [],

                'actions' =>
                    [],

                'mode' =>
                    'error',

                'provider' =>
                    null,

                'model' =>
                    null,

                'token_count' =>
                    null,

                'llm_metadata' =>
                    [],
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | ASSISTANT MESSAGE METADATA
        |--------------------------------------------------------------------------
        */

        $assistantMetadata = [
            'mode' =>
                $result[
                    'mode'
                ]
                ?? 'unknown',

            'sources' =>
                $result[
                    'sources'
                ]
                ?? [],
        ];


        if (
            !empty(
                $result[
                    'actions'
                ]
            )
        ) {
            $assistantMetadata[
                'actions'
            ] =
                $result[
                    'actions'
                ];
        }


        if (
            !empty(
                $result[
                    'provider'
                ]
            )
        ) {
            $assistantMetadata[
                'provider'
            ] =
                $result[
                    'provider'
                ];
        }


        if (
            !empty(
                $result[
                    'model'
                ]
            )
        ) {
            $assistantMetadata[
                'model'
            ] =
                $result[
                    'model'
                ];
        }


        if (
            !empty(
                $result[
                    'llm_metadata'
                ]
            )
        ) {
            $assistantMetadata[
                'llm'
            ] =
                $result[
                    'llm_metadata'
                ];
        }


        /*
        |--------------------------------------------------------------------------
        | ASSISTANT MESSAGE
        |--------------------------------------------------------------------------
        */

        $assistantMessage =
            $conversation
                ->messages()
                ->create([
                    'role' =>
                        ChatMessage::ROLE_ASSISTANT,

                    'content' =>
                        $result[
                            'content'
                        ],

                    'metadata' =>
                        $assistantMetadata,

                    'token_count' =>
                        isset(
                            $result[
                                'token_count'
                            ]
                        )
                        &&
                        $result[
                            'token_count'
                        ] !== null
                            ? (int)
                                $result[
                                    'token_count'
                                ]
                            : null,
                ]);


        /*
        |--------------------------------------------------------------------------
        | UPDATE CONVERSATION
        |--------------------------------------------------------------------------
        */

        if (!$conversation->title) {
            $conversation->title =
                Str::limit(
                    $message,
                    70
                );
        }


        $conversation
            ->last_message_at =
            now();


        $conversation->save();


        /*
        |--------------------------------------------------------------------------
        | JSON RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' =>
                true,

            'conversation_id' =>
                $conversation->id,

            'user_message' => [
                'id' =>
                    $userMessage->id,

                'role' =>
                    $userMessage->role,

                'content' =>
                    $userMessage->content,

                'created_at' =>
                    $userMessage
                        ->created_at
                        ->format(
                            'H:i'
                        ),
            ],

            'assistant_message' => [
                'id' =>
                    $assistantMessage->id,

                'role' =>
                    $assistantMessage->role,

                'content' =>
                    $assistantMessage
                        ->content,

                'created_at' =>
                    $assistantMessage
                        ->created_at
                        ->format(
                            'H:i'
                        ),

                'sources' =>
                    $result[
                        'sources'
                    ]
                    ?? [],

                'actions' =>
                    $result[
                        'actions'
                    ]
                    ?? [],

                'mode' =>
                    $result[
                        'mode'
                    ]
                    ?? 'unknown',

                'provider' =>
                    $result[
                        'provider'
                    ]
                    ?? null,

                'model' =>
                    $result[
                        'model'
                    ]
                    ?? null,

                'token_count' =>
                    $result[
                        'token_count'
                    ]
                    ?? null,
            ],
        ]);
    }


    /**
     * Resolve conversation của
     * phiên đăng nhập hiện tại.
     */
    private function resolveConversation(
        Request $request,
        bool $createIfMissing
    ): ?ChatConversation {
        $user =
            $request->user();


        $conversationId =
            $request
                ->session()
                ->get(
                    'chat_conversation_id'
                );


        /*
        |--------------------------------------------------------------------------
        | CURRENT SESSION CONVERSATION
        |--------------------------------------------------------------------------
        */

        if ($conversationId) {
            $conversation =
                ChatConversation::query()
                    ->where(
                        'id',
                        $conversationId
                    )
                    ->where(
                        'user_id',
                        $user->id
                    )
                    ->first();


            if ($conversation) {
                return $conversation;
            }


            $request
                ->session()
                ->forget(
                    'chat_conversation_id'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | DISPLAY EMPTY CHAT
        |--------------------------------------------------------------------------
        */

        if (!$createIfMissing) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE NEW CONVERSATION
        |--------------------------------------------------------------------------
        */

        $conversation =
            ChatConversation::create([
                'user_id' =>
                    $user->id,

                'session_key' =>
                    $request
                        ->session()
                        ->getId(),

                'title' =>
                    null,

                'last_message_at' =>
                    now(),
            ]);


        $request
            ->session()
            ->put(
                'chat_conversation_id',
                $conversation->id
            );


        return $conversation;
    }
}