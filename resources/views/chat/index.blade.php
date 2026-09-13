@extends('layouts.app')


@section(
    'title',
    'AutoCare AI - Trợ lý bảo dưỡng ô tô'
)


@push('styles')

<style>
    .chat-page {
        max-width: 1240px;
    }

    .chat-shell {
        display: grid;
        grid-template-columns:
            310px
            minmax(0, 1fr);
        min-height: 720px;
        overflow: hidden;
        border:
            1px solid
            rgba(255, 255, 255, 0.88);
        border-radius: 27px;
        background:
            rgba(255, 255, 255, 0.92);
        box-shadow: var(--ac-shadow-lg);
        backdrop-filter: blur(18px);
    }

    /* =====================================================
       SIDEBAR
       ===================================================== */

    .chat-sidebar {
        position: relative;
        overflow: hidden;
        padding: 24px;
        color: white;
        background:
            linear-gradient(
                160deg,
                #050c18,
                #08234c 55%,
                #0f4fb2
            );
    }

    .chat-sidebar::before {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        right: -170px;
        top: -140px;
        border-radius: 50%;
        background:
            radial-gradient(
                circle,
                rgba(103, 232, 249, 0.32),
                transparent 70%
            );
    }

    .chat-sidebar-inner {
        position: relative;
        z-index: 2;
    }

    .chat-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-brand-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        color: #67e8f9;
        background:
            rgba(255, 255, 255, 0.09);
        font-size: 22px;
    }

    .chat-brand-title {
        color: white;
        font-size: 14px;
        font-weight: 900;
    }

    .chat-brand-subtitle {
        margin-top: 2px;
        color: #93c5fd;
        font-size: 9px;
    }

    .chat-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 20px;
        padding: 7px 10px;
        border-radius: 999px;
        color: #d1fae5;
        background:
            rgba(16, 185, 129, 0.13);
        font-size: 9px;
        font-weight: 850;
    }

    .chat-status::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #6ee7b7;
        box-shadow:
            0 0 10px #6ee7b7;
    }

    .chat-sidebar-divider {
        height: 1px;
        margin: 23px 0;
        background:
            rgba(255, 255, 255, 0.10);
    }

    .chat-sidebar-label {
        margin-bottom: 12px;
        color: #93c5fd;
        font-size: 9px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .chat-sidebar-item {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        padding: 10px 0;
        color: #dbeafe;
        font-size: 10px;
        line-height: 1.6;
    }

    .chat-sidebar-item i {
        margin-top: 1px;
        color: #67e8f9;
    }

    .chat-new-button {
        width: 100%;
        min-height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        margin-top: 22px;
        border:
            1px solid
            rgba(255, 255, 255, 0.14);
        border-radius: 12px;
        color: white;
        text-decoration: none;
        background:
            rgba(255, 255, 255, 0.07);
        font-size: 11px;
        font-weight: 850;
        transition:
            background 0.2s ease,
            transform 0.2s ease;
    }

    .chat-new-button:hover {
        color: white;
        background:
            rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }

    /* =====================================================
       MAIN CHAT
       ===================================================== */

    .chat-main {
        min-width: 0;
        display: flex;
        flex-direction: column;
        background:
            linear-gradient(
                180deg,
                rgba(248, 251, 255, 0.92),
                rgba(255, 255, 255, 0.96)
            );
    }

    .chat-header {
        min-height: 82px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 17px 23px;
        border-bottom: 1px solid #e7edf4;
        background:
            rgba(255, 255, 255, 0.76);
        backdrop-filter: blur(15px);
    }

    .chat-header-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .chat-header-avatar {
        width: 43px;
        height: 43px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 8px 20px
            rgba(37, 99, 235, 0.20);
        font-size: 19px;
    }

    .chat-header-title {
        color: #0f172a;
        font-size: 14px;
        font-weight: 900;
    }

    .chat-header-subtitle {
        margin-top: 2px;
        color: #64748b;
        font-size: 9px;
    }

    .chat-engine-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 9px;
        border-radius: 999px;
        color: #6d28d9;
        background: #f5f3ff;
        font-size: 9px;
        font-weight: 850;
    }

    /* =====================================================
       MESSAGES
       ===================================================== */

    .chat-messages {
        flex: 1;
        height: 510px;
        overflow-y: auto;
        padding: 28px;
        scroll-behavior: smooth;
    }

    .chat-empty {
        height: 100%;
        max-width: 650px;
        margin: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .chat-empty-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
        border-radius: 21px;
        color: #2563eb;
        background:
            linear-gradient(
                135deg,
                #dbeafe,
                #ecfeff
            );
        font-size: 29px;
        box-shadow:
            0 15px 35px
            rgba(37, 99, 235, 0.12);
    }

    .chat-empty h2 {
        margin: 0;
        color: #0f172a;
        font-size: 25px;
        font-weight: 950;
        letter-spacing: -0.04em;
    }

    .chat-empty p {
        max-width: 530px;
        margin: 10px auto 0;
        color: #64748b;
        font-size: 11px;
        line-height: 1.75;
    }

    .chat-suggestions {
        width: 100%;
        display: grid;
        grid-template-columns:
            repeat(
                2,
                minmax(0, 1fr)
            );
        gap: 10px;
        margin-top: 23px;
    }

    .chat-suggestion {
        padding: 13px;
        border: 1px solid #e0e8f2;
        border-radius: 13px;
        color: #334155;
        text-align: left;
        background: white;
        font-size: 10px;
        font-weight: 750;
        line-height: 1.55;
        cursor: pointer;
        transition:
            transform 0.2s ease,
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .chat-suggestion:hover {
        transform: translateY(-2px);
        border-color: #93c5fd;
        box-shadow:
            0 9px 23px
            rgba(37, 99, 235, 0.09);
    }

    .chat-message {
        width: 100%;
        display: flex;
        gap: 10px;
        margin-bottom: 22px;
    }

    .chat-message.user {
        justify-content: flex-end;
    }

    .chat-message.assistant {
        justify-content: flex-start;
    }

    .chat-message-avatar {
        width: 34px;
        height: 34px;
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        font-size: 15px;
    }

    .chat-message.assistant
    .chat-message-avatar {
        color: white;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
    }

    .chat-message.user
    .chat-message-avatar {
        order: 2;
        color: #334155;
        background: #e2e8f0;
    }

    .chat-message-content {
        max-width: 76%;
    }

    .chat-message-name {
        margin-bottom: 5px;
        color: #64748b;
        font-size: 9px;
        font-weight: 850;
    }

    .chat-message.user
    .chat-message-name {
        text-align: right;
    }

    .chat-bubble {
        padding: 13px 15px;
        border-radius: 16px;
        font-size: 11px;
        line-height: 1.75;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .chat-message.assistant
    .chat-bubble {
        color: #334155;
        border: 1px solid #e2e8f0;
        border-top-left-radius: 5px;
        background: white;
        box-shadow:
            0 6px 20px
            rgba(15, 23, 42, 0.05);
    }

    .chat-message.user
    .chat-bubble {
        color: white;
        border-top-right-radius: 5px;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 8px 22px
            rgba(37, 99, 235, 0.18);
    }

    .chat-message-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .chat-message-action {
        min-height: 39px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 13px;
        border:
            1px solid
            rgba(37, 99, 235, 0.18);
        border-radius: 11px;
        color: white;
        text-decoration: none;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 7px 18px
            rgba(37, 99, 235, 0.16);
        font-size: 10px;
        font-weight: 850;
        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .chat-message-action:hover {
        color: white;
        transform:
            translateY(-2px);
        box-shadow:
            0 11px 24px
            rgba(37, 99, 235, 0.24);
    }

    .chat-message-action i {
        font-size: 12px;
    }

    .chat-message-time {
        margin-top: 5px;
        color: #94a3b8;
        font-size: 8px;
    }

    .chat-message.user
    .chat-message-time {
        text-align: right;
    }

    .chat-thinking {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .chat-thinking-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #94a3b8;
        animation:
            chatThinking 1s infinite ease-in-out;
    }

    .chat-thinking-dot:nth-child(2) {
        animation-delay: 0.15s;
    }

    .chat-thinking-dot:nth-child(3) {
        animation-delay: 0.3s;
    }

    @keyframes chatThinking {
        0%,
        100% {
            opacity: 0.3;
            transform: translateY(0);
        }

        50% {
            opacity: 1;
            transform: translateY(-3px);
        }
    }

    /* =====================================================
       INPUT
       ===================================================== */

    .chat-composer {
        padding: 18px 22px 20px;
        border-top: 1px solid #e7edf4;
        background:
            rgba(255, 255, 255, 0.9);
    }

    .chat-form {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        padding: 8px;
        border: 1px solid #dce5f0;
        border-radius: 17px;
        background: white;
        box-shadow:
            0 10px 30px
            rgba(15, 23, 42, 0.06);
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .chat-form:focus-within {
        border-color: #93c5fd;
        box-shadow:
            0 12px 35px
            rgba(37, 99, 235, 0.10);
    }

    .chat-input {
        flex: 1;
        min-height: 46px;
        max-height: 130px;
        padding: 12px;
        border: none;
        outline: none;
        resize: none;
        color: #0f172a;
        background: transparent;
        font-size: 12px;
        line-height: 1.55;
    }

    .chat-send {
        width: 45px;
        height: 45px;
        flex: 0 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        border-radius: 13px;
        color: white;
        background:
            linear-gradient(
                135deg,
                #1683ff,
                #4f46e5
            );
        box-shadow:
            0 8px 20px
            rgba(37, 99, 235, 0.20);
        cursor: pointer;
        transition:
            transform 0.2s ease,
            opacity 0.2s ease;
    }

    .chat-send:hover {
        transform: translateY(-2px);
    }

    .chat-send:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none;
    }

    .chat-disclaimer {
        margin-top: 8px;
        color: #94a3b8;
        text-align: center;
        font-size: 8px;
        line-height: 1.5;
    }

    @media (max-width: 991px) {
        .chat-shell {
            grid-template-columns: 1fr;
        }

        .chat-sidebar {
            display: none;
        }
    }

    @media (max-width: 575px) {
        .chat-shell {
            min-height: 650px;
            border-radius: 20px;
        }

        .chat-header {
            padding: 14px 16px;
        }

        .chat-engine-badge {
            display: none;
        }

        .chat-messages {
            height: 470px;
            padding: 20px 14px;
        }

        .chat-message-content {
            max-width: 88%;
        }

        .chat-suggestions {
            grid-template-columns: 1fr;
        }

        .chat-composer {
            padding: 13px;
        }

        .chat-message-action {
            width: 100%;
        }
    }
</style>

@endpush


@section('content')

<div class="container chat-page">

    <div
        class="chat-shell"
        data-reveal="zoom"
    >

        <aside class="chat-sidebar">

            <div class="chat-sidebar-inner">

                <div class="chat-brand">

                    <div class="chat-brand-icon">
                        <i class="bi bi-robot"></i>
                    </div>

                    <div>

                        <div class="chat-brand-title">
                            AutoCare AI
                        </div>

                        <div class="chat-brand-subtitle">
                            Automotive Assistant
                        </div>

                    </div>

                </div>


                <div class="chat-status">
                    Knowledge Base Online
                </div>


                <div class="chat-sidebar-divider"></div>


                <div class="chat-sidebar-label">
                    Có thể hỗ trợ
                </div>


                <div class="chat-sidebar-item">

                    <i class="bi bi-tools"></i>

                    <span>
                        Thông tin dịch vụ
                        và giá tham khảo.
                    </span>

                </div>


                <div class="chat-sidebar-item">

                    <i class="bi bi-speedometer2"></i>

                    <span>
                        Chu kỳ bảo dưỡng
                        theo ODO và thời gian.
                    </span>

                </div>


                <div class="chat-sidebar-item">

                    <i class="bi bi-calendar2-check"></i>

                    <span>
                        Quy trình đặt lịch
                        và tiếp nhận xe.
                    </span>

                </div>


                <div class="chat-sidebar-item">

                    <i class="bi bi-receipt"></i>

                    <span>
                        Hóa đơn và
                        quy trình thanh toán.
                    </span>

                </div>


                <div class="chat-sidebar-divider"></div>


                @if ($conversation)

                    <div class="chat-sidebar-label">
                        Hội thoại hiện tại
                    </div>

                    <div class="chat-sidebar-item">

                        <i class="bi bi-chat-left-text"></i>

                        <span>
                            {{
                                $conversation->title
                                ?? 'Cuộc trò chuyện mới'
                            }}
                        </span>

                    </div>

                @endif


                <a
                    href="{{ route(
                        'chat.index',
                        ['new' => 1]
                    ) }}"
                    class="chat-new-button"
                >

                    <i class="bi bi-plus-circle"></i>

                    Cuộc trò chuyện mới

                </a>

            </div>

        </aside>


        <section class="chat-main">

            <header class="chat-header">

                <div class="chat-header-left">

                    <div class="chat-header-avatar">

                        <i class="bi bi-stars"></i>

                    </div>


                    <div>

                        <div class="chat-header-title">
                            AutoCare AI
                        </div>

                        <div class="chat-header-subtitle">
                            Trợ lý hỗ trợ bảo dưỡng ô tô
                        </div>

                    </div>

                </div>


                <div class="chat-engine-badge">

                    <i class="bi bi-database-check"></i>

                    Knowledge Search

                </div>

            </header>


            <div
                id="chatMessages"
                class="chat-messages"
            >

                @if ($messages->isEmpty())

                    <div
                        id="chatEmpty"
                        class="chat-empty"
                    >

                        <div class="chat-empty-icon">
                            <i class="bi bi-robot"></i>
                        </div>


                        <h2>
                            Xin chào,
                            {{ auth()->user()->name }}
                        </h2>


                        <p>

                            Mình là AutoCare AI.
                            Hãy hỏi về dịch vụ,
                            chi phí, chu kỳ bảo dưỡng
                            hoặc cách sử dụng hệ thống.

                        </p>


                        <div class="chat-suggestions">

                            <button
                                type="button"
                                class="chat-suggestion"
                                data-question="AutoCare có những dịch vụ bảo dưỡng nào?"
                            >
                                <i class="bi bi-tools me-1"></i>

                                AutoCare có những dịch vụ
                                bảo dưỡng nào?
                            </button>


                            <button
                                type="button"
                                class="chat-suggestion"
                                data-question="Thay dầu động cơ giá bao nhiêu?"
                            >
                                <i class="bi bi-cash-stack me-1"></i>

                                Thay dầu động cơ
                                giá bao nhiêu?
                            </button>


                            <button
                                type="button"
                                class="chat-suggestion"
                                data-question="Quy trình đặt lịch bảo dưỡng như thế nào?"
                            >
                                <i class="bi bi-calendar2-check me-1"></i>

                                Quy trình đặt lịch
                                như thế nào?
                            </button>


                            <button
                                type="button"
                                class="chat-suggestion"
                                data-question="Bao lâu nên bảo dưỡng ô tô?"
                            >
                                <i class="bi bi-speedometer2 me-1"></i>

                                Bao lâu nên
                                bảo dưỡng ô tô?
                            </button>

                        </div>

                    </div>

                @else

                    @foreach ($messages as $message)

                        @php
                            $isUser =
                                $message->role
                                === 'USER';

                            $messageMetadata =
                                is_array(
                                    $message->metadata
                                )
                                    ? $message->metadata
                                    : [];

                            $messageActions =
                                !$isUser
                                    ? (
                                        $messageMetadata[
                                            'actions'
                                        ]
                                        ?? []
                                    )
                                    : [];
                        @endphp


                        <div
                            class="
                                chat-message
                                {{
                                    $isUser
                                        ? 'user'
                                        : 'assistant'
                                }}
                            "
                        >

                            <div class="chat-message-avatar">

                                <i
                                    class="
                                        bi
                                        {{
                                            $isUser
                                                ? 'bi-person-fill'
                                                : 'bi-robot'
                                        }}
                                    "
                                ></i>

                            </div>


                            <div class="chat-message-content">

                                <div class="chat-message-name">

                                    {{
                                        $isUser
                                            ? 'Bạn'
                                            : 'AutoCare AI'
                                    }}

                                </div>


                                <div class="chat-bubble">{{ $message->content }}</div>


                                @if (
                                    !empty(
                                        $messageActions
                                    )
                                )

                                    <div class="chat-message-actions">

                                        @foreach (
                                            $messageActions
                                            as $action
                                        )

                                            @if (
                                                ($action['type'] ?? null)
                                                === 'link'
                                                &&
                                                !empty(
                                                    $action['url']
                                                )
                                                &&
                                                !empty(
                                                    $action['label']
                                                )
                                            )

                                                <a
                                                    href="{{ $action['url'] }}"
                                                    class="chat-message-action"
                                                >

                                                    <i
                                                        class="
                                                            bi
                                                            {{
                                                                $action['icon']
                                                                ?? 'bi-arrow-right-circle'
                                                            }}
                                                        "
                                                    ></i>

                                                    {{ $action['label'] }}

                                                </a>

                                            @endif

                                        @endforeach

                                    </div>

                                @endif


                                <div class="chat-message-time">

                                    {{
                                        $message
                                            ->created_at
                                            ->format('H:i')
                                    }}

                                </div>

                            </div>

                        </div>

                    @endforeach

                @endif

            </div>


            <footer class="chat-composer">

                <form
                    id="chatForm"
                    class="chat-form"
                >

                    <textarea
                        id="chatInput"
                        class="chat-input"
                        rows="1"
                        maxlength="2000"
                        placeholder="Hỏi AutoCare AI..."
                        required
                    ></textarea>


                    <button
                        id="chatSendButton"
                        type="submit"
                        class="chat-send"
                        aria-label="Gửi tin nhắn"
                    >

                        <i class="bi bi-send-fill"></i>

                    </button>

                </form>


                <div class="chat-disclaimer">

                    AutoCare AI sử dụng dữ liệu nội bộ
                    và AI để hỗ trợ người dùng.
                    Các tư vấn kỹ thuật chỉ mang tính hỗ trợ.

                </div>

            </footer>

        </section>

    </div>

</div>

@endsection


@push('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const chatForm =
                document.getElementById(
                    'chatForm'
                );

            const chatInput =
                document.getElementById(
                    'chatInput'
                );

            const chatMessages =
                document.getElementById(
                    'chatMessages'
                );

            const sendButton =
                document.getElementById(
                    'chatSendButton'
                );

            const csrfToken =
                document
                    .querySelector(
                        'meta[name="csrf-token"]'
                    )
                    .getAttribute(
                        'content'
                    );


            function scrollToBottom() {
                chatMessages.scrollTop =
                    chatMessages.scrollHeight;
            }


            function removeEmptyState() {
                const emptyState =
                    document.getElementById(
                        'chatEmpty'
                    );

                if (emptyState) {
                    emptyState.remove();
                }
            }


            function appendActions(
                contentWrapper,
                actions
            ) {
                if (
                    !Array.isArray(actions)
                    ||
                    actions.length === 0
                ) {
                    return;
                }


                const actionsWrapper =
                    document.createElement(
                        'div'
                    );

                actionsWrapper.className =
                    'chat-message-actions';


                actions.forEach(
                    function (action) {
                        if (
                            !action
                            ||
                            action.type !== 'link'
                            ||
                            !action.url
                            ||
                            !action.label
                        ) {
                            return;
                        }


                        const link =
                            document.createElement(
                                'a'
                            );

                        link.className =
                            'chat-message-action';

                        link.href =
                            action.url;


                        const icon =
                            document.createElement(
                                'i'
                            );

                        icon.className =
                            `bi ${
                                action.icon
                                || 'bi-arrow-right-circle'
                            }`;


                        const label =
                            document.createElement(
                                'span'
                            );

                        label.textContent =
                            action.label;


                        link.appendChild(
                            icon
                        );

                        link.appendChild(
                            label
                        );

                        actionsWrapper
                            .appendChild(
                                link
                            );
                    }
                );


                if (
                    actionsWrapper
                        .children
                        .length
                    > 0
                ) {
                    contentWrapper
                        .appendChild(
                            actionsWrapper
                        );
                }
            }


            function createMessage(
                role,
                content,
                time = '',
                actions = []
            ) {
                const wrapper =
                    document.createElement(
                        'div'
                    );

                wrapper.className =
                    `chat-message ${role}`;


                const avatar =
                    document.createElement(
                        'div'
                    );

                avatar.className =
                    'chat-message-avatar';


                const avatarIcon =
                    document.createElement(
                        'i'
                    );

                avatarIcon.className =
                    role === 'user'
                        ? 'bi bi-person-fill'
                        : 'bi bi-robot';


                avatar.appendChild(
                    avatarIcon
                );


                const contentWrapper =
                    document.createElement(
                        'div'
                    );

                contentWrapper.className =
                    'chat-message-content';


                const name =
                    document.createElement(
                        'div'
                    );

                name.className =
                    'chat-message-name';

                name.textContent =
                    role === 'user'
                        ? 'Bạn'
                        : 'AutoCare AI';


                const bubble =
                    document.createElement(
                        'div'
                    );

                bubble.className =
                    'chat-bubble';

                bubble.textContent =
                    content;


                const timeElement =
                    document.createElement(
                        'div'
                    );

                timeElement.className =
                    'chat-message-time';

                timeElement.textContent =
                    time;


                contentWrapper.appendChild(
                    name
                );

                contentWrapper.appendChild(
                    bubble
                );


                if (
                    role === 'assistant'
                ) {
                    appendActions(
                        contentWrapper,
                        actions
                    );
                }


                contentWrapper.appendChild(
                    timeElement
                );


                wrapper.appendChild(
                    avatar
                );

                wrapper.appendChild(
                    contentWrapper
                );


                chatMessages.appendChild(
                    wrapper
                );


                scrollToBottom();


                return wrapper;
            }


            function createThinkingMessage() {
                const wrapper =
                    document.createElement(
                        'div'
                    );

                wrapper.className =
                    'chat-message assistant';

                wrapper.id =
                    'chatThinkingMessage';


                const avatar =
                    document.createElement(
                        'div'
                    );

                avatar.className =
                    'chat-message-avatar';

                avatar.innerHTML =
                    '<i class="bi bi-robot"></i>';


                const contentWrapper =
                    document.createElement(
                        'div'
                    );

                contentWrapper.className =
                    'chat-message-content';


                const name =
                    document.createElement(
                        'div'
                    );

                name.className =
                    'chat-message-name';

                name.textContent =
                    'AutoCare AI';


                const bubble =
                    document.createElement(
                        'div'
                    );

                bubble.className =
                    'chat-bubble';


                const thinking =
                    document.createElement(
                        'span'
                    );

                thinking.className =
                    'chat-thinking';

                thinking.innerHTML = `
                    <span class="chat-thinking-dot"></span>
                    <span class="chat-thinking-dot"></span>
                    <span class="chat-thinking-dot"></span>
                `;


                bubble.appendChild(
                    thinking
                );

                contentWrapper.appendChild(
                    name
                );

                contentWrapper.appendChild(
                    bubble
                );

                wrapper.appendChild(
                    avatar
                );

                wrapper.appendChild(
                    contentWrapper
                );

                chatMessages.appendChild(
                    wrapper
                );

                scrollToBottom();
            }


            function removeThinkingMessage() {
                const thinking =
                    document.getElementById(
                        'chatThinkingMessage'
                    );

                if (thinking) {
                    thinking.remove();
                }
            }


            function currentTime() {
                return new Intl
                    .DateTimeFormat(
                        'vi-VN',
                        {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false,
                        }
                    )
                    .format(
                        new Date()
                    );
            }


            async function sendMessage(
                message
            ) {
                removeEmptyState();

                createMessage(
                    'user',
                    message,
                    currentTime()
                );


                chatInput.value = '';

                chatInput.style.height =
                    'auto';

                sendButton.disabled =
                    true;


                createThinkingMessage();


                try {
                    const response =
                        await fetch(
                            @json(
                                route(
                                    'chat.messages.store'
                                )
                            ),
                            {
                                method: 'POST',

                                headers: {
                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        csrfToken,
                                },

                                body:
                                    JSON.stringify({
                                        message:
                                            message,
                                    }),
                            }
                        );


                    const data =
                        await response.json();


                    removeThinkingMessage();


                    if (!response.ok) {
                        throw new Error(
                            data.message
                            || 'Không thể gửi tin nhắn.'
                        );
                    }


                    createMessage(
                        'assistant',
                        data
                            .assistant_message
                            .content,
                        data
                            .assistant_message
                            .created_at,
                        data
                            .assistant_message
                            .actions
                        || []
                    );
                }
                catch (error) {
                    removeThinkingMessage();

                    createMessage(
                        'assistant',
                        'Đã xảy ra lỗi khi gửi câu hỏi. Vui lòng thử lại.',
                        currentTime()
                    );

                    console.error(
                        error
                    );
                }
                finally {
                    sendButton.disabled =
                        false;

                    chatInput.focus();
                }
            }


            chatForm.addEventListener(
                'submit',
                function (event) {
                    event.preventDefault();

                    const message =
                        chatInput
                            .value
                            .trim();

                    if (
                        !message
                        ||
                        sendButton.disabled
                    ) {
                        return;
                    }


                    sendMessage(
                        message
                    );
                }
            );


            chatInput.addEventListener(
                'keydown',
                function (event) {
                    if (
                        event.key === 'Enter'
                        &&
                        !event.shiftKey
                    ) {
                        event.preventDefault();

                        chatForm.requestSubmit();
                    }
                }
            );


            chatInput.addEventListener(
                'input',
                function () {
                    this.style.height =
                        'auto';

                    this.style.height =
                        Math.min(
                            this.scrollHeight,
                            130
                        )
                        + 'px';
                }
            );


            document
                .querySelectorAll(
                    '[data-question]'
                )
                .forEach(
                    function (button) {
                        button.addEventListener(
                            'click',
                            function () {
                                chatInput.value =
                                    this.dataset.question;

                                chatForm
                                    .requestSubmit();
                            }
                        );
                    }
                );


            scrollToBottom();

            chatInput.focus();
        }
    );
</script>

@endpush