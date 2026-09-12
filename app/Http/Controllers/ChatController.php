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
         * Tạo cuộc trò chuyện mới.
         *
         * GET /chat?new=1
         */
        if ($request->boolean('new')) {
            $request
                ->session()
                ->forget(
                    'chat_conversation_id'
                );
        }


        $conversation =
            $this->resolveConversation(
                $request,
                false
            );


        $messages =
            $conversation
                ? $conversation
                    ->messages()
                    ->orderBy('created_at')
                    ->orderBy('id')
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
     * Nhận tin nhắn người dùng và
     * trả về phản hồi chatbot.
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
                $validated['message']
            );


        $conversation =
            $this->resolveConversation(
                $request,
                true
            );


        /*
         * Lưu tin nhắn người dùng.
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


        try {
            $result =
                $chatService->reply(
                    $message,
                    $user
                );
        }
        catch (Throwable $exception) {
            report($exception);


            $result = [
                'content' => implode(
                    ' ',
                    [
                        'AutoCare AI đang gặp sự cố khi xử lý câu hỏi.',
                        'Vui lòng thử lại sau ít phút.',
                    ]
                ),

                'sources' => [],

                'mode' => 'error',
            ];
        }


        /*
         * Lưu phản hồi chatbot.
         */
        $assistantMessage =
            $conversation
                ->messages()
                ->create([
                    'role' =>
                        ChatMessage::ROLE_ASSISTANT,

                    'content' =>
                        $result['content'],

                    'metadata' => [
                        'mode' =>
                            $result['mode']
                            ?? 'fallback',

                        'sources' =>
                            $result['sources']
                            ?? [],
                    ],

                    'token_count' =>
                        null,
                ]);


        /*
         * Tin nhắn đầu tiên được dùng
         * làm tiêu đề cuộc trò chuyện.
         */
        if (!$conversation->title) {
            $conversation->title =
                Str::limit(
                    $message,
                    70
                );
        }


        $conversation->last_message_at =
            now();

        $conversation->save();


        return response()->json([
            'success' => true,

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
                        ->format('H:i'),
            ],

            'assistant_message' => [
                'id' =>
                    $assistantMessage->id,

                'role' =>
                    $assistantMessage->role,

                'content' =>
                    $assistantMessage->content,

                'created_at' =>
                    $assistantMessage
                        ->created_at
                        ->format('H:i'),

                'sources' =>
                    $result['sources']
                    ?? [],

                'mode' =>
                    $result['mode']
                    ?? 'fallback',
            ],
        ]);
    }


    /**
     * Lấy conversation hiện tại.
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
         * Ưu tiên conversation đang lưu
         * trong session nhưng bắt buộc
         * phải thuộc user hiện tại.
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
         * Khi vào /chat bình thường,
         * mở lại cuộc hội thoại gần nhất.
         */
        if (!$createIfMissing) {
            $conversation =
                ChatConversation::query()
                    ->where(
                        'user_id',
                        $user->id
                    )
                    ->latest(
                        'last_message_at'
                    )
                    ->latest('id')
                    ->first();


            if ($conversation) {
                $request
                    ->session()
                    ->put(
                        'chat_conversation_id',
                        $conversation->id
                    );


                return $conversation;
            }


            return null;
        }


        /*
         * Tạo conversation khi user
         * gửi tin nhắn đầu tiên.
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