<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Invoice;
use App\Models\ServiceOrder;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ConversationContextService
{
    /**
     * Lấy các message trước message hiện tại.
     */
    public function recentMessages(
        ChatConversation $conversation,
        ?int $beforeMessageId = null,
        ?int $limit = null
    ): Collection {
        $limit =
            max(
                0,
                $limit
                ?? (int) config(
                    'ai.conversation.history_message_limit',
                    8
                )
            );

        if ($limit === 0) {
            return collect();
        }

        $query =
            $conversation
                ->messages()
                ->whereIn(
                    'role',
                    [
                        ChatMessage::ROLE_USER,
                        ChatMessage::ROLE_ASSISTANT,
                    ]
                );

        if ($beforeMessageId !== null) {
            $query->where(
                'id',
                '<',
                $beforeMessageId
            );
        }

        return $query
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }


    /**
     * Chuyển history sang format LLM.
     */
    public function toLlmMessages(
        Collection $messages
    ): array {
        return $messages
            ->map(
                function (
                    ChatMessage $message
                ) {
                    $content =
                        $this->sanitizeContent(
                            $message->content
                        );

                    if ($content === '') {
                        return null;
                    }

                    return [
                        'role' =>
                            $message->role
                            === ChatMessage::ROLE_ASSISTANT
                                ? 'assistant'
                                : 'user',

                        'content' =>
                            $content,
                    ];
                }
            )
            ->filter()
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | CUSTOMER FOLLOW-UP RESOLUTION
    |--------------------------------------------------------------------------
    |
    | KHÔNG ghép toàn bộ history vào câu hỏi.
    |
    | Thay vào đó:
    |
    | 1. Xác định current message có thực sự
    |    là câu follow-up dữ liệu khách hàng.
    |
    | 2. Lấy vehicle_id từ metadata/sources
    |    của câu trả lời trước.
    |
    | 3. Nếu user nói "Accent" thì match
    |    chính xác Accent.
    |
    | 4. Nếu chỉ nói "nó" và chỉ có 1 xe
    |    trong context thì resolve xe đó.
    |
    | 5. Nếu có nhiều xe mà user chỉ nói
    |    "nó" thì yêu cầu làm rõ.
    |
    */

    public function resolveCustomerFollowUp(
        string $currentMessage,
        Collection $history,
        User $user
    ): array {
        $intent =
            $this->detectCustomerFollowUpIntent(
                $currentMessage
            );

        if ($intent === null) {
            return [
                'status' => 'none',
            ];
        }

        $vehicles =
            $this->latestVehicleReferences(
                $history,
                $user
            );

        if ($vehicles->isEmpty()) {
            return [
                'status' => 'none',
            ];
        }

        /*
         * User có nhắc rõ Accent,
         * BMW, biển số... trong câu hiện tại.
         */
        $matchedVehicle =
            $this->matchVehicleFromMessage(
                $currentMessage,
                $vehicles
            );

        if ($matchedVehicle) {
            return [
                'status' =>
                    'resolved',

                'query' =>
                    $this->buildCustomerResolvedQuery(
                        $intent,
                        $matchedVehicle,
                        $currentMessage
                    ),

                'vehicle' =>
                    $matchedVehicle,

                'intent' =>
                    $intent,
            ];
        }

        /*
         * Không nhắc tên xe nhưng chỉ có
         * duy nhất 1 xe trong context.
         *
         * Ví dụ:
         * "Nó đã bảo dưỡng chưa?"
         */
        if ($vehicles->count() === 1) {
            $vehicle =
                $vehicles->first();

            return [
                'status' =>
                    'resolved',

                'query' =>
                    $this->buildCustomerResolvedQuery(
                        $intent,
                        $vehicle,
                        $currentMessage
                    ),

                'vehicle' =>
                    $vehicle,

                'intent' =>
                    $intent,
            ];
        }

        /*
         * Có nhiều xe và câu hỏi mơ hồ.
         *
         * Không tự chọn một chiếc.
         */
        return [
            'status' =>
                'ambiguous',

            'vehicles' =>
                $vehicles,

            'intent' =>
                $intent,
        ];
    }


    /**
     * Chỉ nhận diện các câu thật sự
     * có dấu hiệu hỏi dữ liệu cá nhân.
     *
     * Không xem mọi câu ngắn là follow-up.
     */
    private function detectCustomerFollowUpIntent(
        string $message
    ): ?string {
        $message =
            $this->normalize(
                $message
            );

        /*
         * ODO / thông tin xe.
         */
        if (
            $this->containsAny(
                $message,
                [
                    'odo',
                    'so km',
                    'kilomet',
                    'bien so',
                    'vin',
                    'xe gi',
                    'xe nao',
                ]
            )
        ) {
            return 'VEHICLE';
        }

        /*
         * Recommendation.
         *
         * Kiểm tra trước maintenance history.
         */
        if (
            $this->containsAny(
                $message,
                [
                    'nen kiem tra',
                    'can kiem tra',
                    'kiem tra gi',
                    'neu chua',
                    'sap can bao duong',
                    'can bao duong gi',
                    'nen bao duong gi',
                    'bao duong tiep theo',
                    'toi han',
                    'den han',
                    'tiep theo lam gi',
                ]
            )
        ) {
            return 'RECOMMENDATION';
        }

        /*
         * Maintenance history.
         */
        if (
            $this->containsAny(
                $message,
                [
                    'da bao duong',
                    'bao duong chua',
                    'bao duong lan nao',
                    'lan nao chua',
                    'lich su bao duong',
                    'lan bao duong',
                    'bao duong gan nhat',
                ]
            )
        ) {
            return 'MAINTENANCE_HISTORY';
        }

        /*
         * Invoice.
         */
        if (
            $this->containsAny(
                $message,
                [
                    'hoa don',
                    'thanh toan',
                    'con no',
                ]
            )
            &&
            $this->containsFollowUpSignal(
                $message
            )
        ) {
            return 'INVOICE';
        }

        /*
         * Appointment.
         */
        if (
            $this->containsAny(
                $message,
                [
                    'lich hen',
                    'dat lich',
                ]
            )
            &&
            $this->containsFollowUpSignal(
                $message
            )
        ) {
            return 'APPOINTMENT';
        }

        return null;
    }


    /**
     * Tạo query sạch cho
     * CustomerContextService.
     *
     * Chỉ chứa:
     * - intent
     * - đúng 1 xe
     * - câu hiện tại
     *
     * KHÔNG chứa toàn bộ history.
     */
    private function buildCustomerResolvedQuery(
        string $intent,
        Vehicle $vehicle,
        string $currentMessage
    ): string {
        $vehicleName =
            $this->vehicleName(
                $vehicle
            );

        $intentCue =
            match ($intent) {
                'RECOMMENDATION' =>
                    'Gợi ý bảo dưỡng cho xe của tôi',

                'MAINTENANCE_HISTORY' =>
                    'Lịch sử bảo dưỡng của xe của tôi',

                'INVOICE' =>
                    'Hóa đơn của xe của tôi',

                'APPOINTMENT' =>
                    'Lịch hẹn của xe của tôi',

                default =>
                    'ODO hiện tại xe của tôi',
            };

        return implode(
            "\n",
            [
                $intentCue . '.',

                'Xe cụ thể: '
                    . $vehicleName
                    . ' - '
                    . $vehicle->license_plate
                    . '.',

                'Câu hỏi hiện tại: '
                    . $currentMessage,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VEHICLE REFERENCES FROM MESSAGE METADATA
    |--------------------------------------------------------------------------
    */

    private function latestVehicleReferences(
        Collection $history,
        User $user
    ): Collection {
        $user->loadMissing(
            'customer'
        );

        $customer =
            $user->customer;

        if (!$customer) {
            return collect();
        }

        /*
         * Đi từ message mới nhất ngược lại.
         *
         * Gặp assistant message đầu tiên
         * có source liên quan vehicle thì dùng.
         */
        foreach (
            $history
                ->reverse()
                ->values()
            as $message
        ) {
            if (
                $message->role
                !==
                ChatMessage::ROLE_ASSISTANT
            ) {
                continue;
            }

            $metadata =
                $this->metadataArray(
                    $message->metadata
                );

            $sources =
                $metadata['sources']
                ?? [];

            if (!is_array($sources)) {
                continue;
            }

            $vehicleIds =
                $this->vehicleIdsFromSources(
                    $sources,
                    $customer->id
                );

            if (empty($vehicleIds)) {
                continue;
            }

            $vehiclesById =
                Vehicle::query()
                    ->with([
                        'brand',
                        'vehicleModel',
                    ])
                    ->where(
                        'customer_id',
                        $customer->id
                    )
                    ->whereIn(
                        'id',
                        $vehicleIds
                    )
                    ->get()
                    ->keyBy('id');

            /*
             * Giữ nguyên thứ tự source.
             */
            $vehicles =
                collect(
                    $vehicleIds
                )
                    ->unique()
                    ->map(
                        fn ($id) =>
                            $vehiclesById->get(
                                (int) $id
                            )
                    )
                    ->filter()
                    ->values();

            if ($vehicles->isNotEmpty()) {
                return $vehicles;
            }
        }

        return collect();
    }


    private function vehicleIdsFromSources(
        array $sources,
        int $customerId
    ): array {
        $vehicleIds = [];

        $appointmentIds = [];
        $serviceOrderIds = [];
        $invoiceIds = [];

        foreach ($sources as $source) {
            if (!is_array($source)) {
                continue;
            }

            $type =
                strtoupper(
                    (string)
                    (
                        $source['type']
                        ?? ''
                    )
                );

            /*
             * Vehicle source.
             */
            if (
                $type === 'VEHICLE'
                &&
                isset($source['id'])
            ) {
                $vehicleIds[] =
                    (int) $source['id'];

                continue;
            }

            /*
             * Recommendation / initial inspection.
             */
            if (
                in_array(
                    $type,
                    [
                        'INITIAL_INSPECTION',
                        'MAINTENANCE_RECOMMENDATION',
                    ],
                    true
                )
                &&
                isset(
                    $source['vehicle_id']
                )
            ) {
                $vehicleIds[] =
                    (int)
                    $source['vehicle_id'];

                continue;
            }

            if (
                $type === 'APPOINTMENT'
                &&
                isset($source['id'])
            ) {
                $appointmentIds[] =
                    (int) $source['id'];

                continue;
            }

            if (
                $type === 'SERVICE_ORDER'
                &&
                isset($source['id'])
            ) {
                $serviceOrderIds[] =
                    (int) $source['id'];

                continue;
            }

            if (
                $type === 'INVOICE'
                &&
                isset($source['id'])
            ) {
                $invoiceIds[] =
                    (int) $source['id'];
            }
        }

        if (!empty($appointmentIds)) {
            $appointmentVehicleIds =
                Appointment::query()
                    ->where(
                        'customer_id',
                        $customerId
                    )
                    ->whereIn(
                        'id',
                        $appointmentIds
                    )
                    ->pluck(
                        'vehicle_id'
                    )
                    ->all();

            $vehicleIds =
                array_merge(
                    $vehicleIds,
                    $appointmentVehicleIds
                );
        }

        if (!empty($serviceOrderIds)) {
            $serviceOrderVehicleIds =
                ServiceOrder::query()
                    ->where(
                        'customer_id',
                        $customerId
                    )
                    ->whereIn(
                        'id',
                        $serviceOrderIds
                    )
                    ->pluck(
                        'vehicle_id'
                    )
                    ->all();

            $vehicleIds =
                array_merge(
                    $vehicleIds,
                    $serviceOrderVehicleIds
                );
        }

        if (!empty($invoiceIds)) {
            $invoiceVehicleIds =
                Invoice::query()
                    ->with([
                        'serviceOrder:id,vehicle_id',
                    ])
                    ->where(
                        'customer_id',
                        $customerId
                    )
                    ->whereIn(
                        'id',
                        $invoiceIds
                    )
                    ->get()
                    ->map(
                        fn ($invoice) =>
                            $invoice
                                ->serviceOrder
                                ?->vehicle_id
                    )
                    ->filter()
                    ->all();

            $vehicleIds =
                array_merge(
                    $vehicleIds,
                    $invoiceVehicleIds
                );
        }

        return collect(
            $vehicleIds
        )
            ->filter()
            ->map(
                fn ($id) =>
                    (int) $id
            )
            ->unique()
            ->values()
            ->all();
    }


    /**
     * Match xe được user nhắc rõ.
     *
     * Ví dụ:
     * "chiếc Accent"
     * → Hyundai Accent.
     */
    private function matchVehicleFromMessage(
        string $message,
        Collection $vehicles
    ): ?Vehicle {
        $question =
            $this->normalize(
                $message
            );

        $ranked =
            $vehicles
                ->map(
                    function (
                        Vehicle $vehicle
                    ) use (
                        $question
                    ) {
                        $score = 0;

                        $brand =
                            $this->normalize(
                                $vehicle
                                    ->brand
                                    ?->name
                                ?? ''
                            );

                        $model =
                            $this->normalize(
                                $vehicle
                                    ->vehicleModel
                                    ?->name
                                ?? ''
                            );

                        $plate =
                            $this->normalize(
                                $vehicle
                                    ->license_plate
                                ?? ''
                            );

                        if (
                            $plate !== ''
                            &&
                            Str::contains(
                                $question,
                                $plate
                            )
                        ) {
                            $score += 100;
                        }

                        if (
                            $model !== ''
                            &&
                            Str::contains(
                                $question,
                                $model
                            )
                        ) {
                            $score += 50;
                        }

                        if (
                            $brand !== ''
                            &&
                            Str::contains(
                                $question,
                                $brand
                            )
                        ) {
                            $score += 20;
                        }

                        /*
                         * Match từng token model.
                         *
                         * Ví dụ:
                         * vehicleModel = Accent
                         * user = "chiếc accent"
                         */
                        $modelTokens =
                            preg_split(
                                '/\s+/',
                                $model,
                                -1,
                                PREG_SPLIT_NO_EMPTY
                            )
                            ?: [];

                        foreach (
                            $modelTokens
                            as $token
                        ) {
                            if (
                                strlen($token) >= 3
                                &&
                                Str::contains(
                                    $question,
                                    $token
                                )
                            ) {
                                $score += 10;
                            }
                        }

                        return [
                            'vehicle' =>
                                $vehicle,

                            'score' =>
                                $score,
                        ];
                    }
                )
                ->sortByDesc(
                    'score'
                )
                ->values();

        $best =
            $ranked->first();

        if (
            !$best
            ||
            $best['score'] <= 0
        ) {
            return null;
        }

        return $best['vehicle'];
    }


    /*
    |--------------------------------------------------------------------------
    | KNOWLEDGE / SERVICE FOLLOW-UP
    |--------------------------------------------------------------------------
    */

    public function buildRetrievalQuery(
        string $currentMessage,
        Collection $history
    ): array {
        if (
            !$this->isKnowledgeFollowUp(
                $currentMessage
            )
        ) {
            return [
                'query' =>
                    $currentMessage,

                'uses_history' =>
                    false,

                'reference' =>
                    null,
            ];
        }

        $services =
            $this->latestServiceReferences(
                $history
            );

        if ($services->isEmpty()) {
            return [
                'query' =>
                    $currentMessage,

                'uses_history' =>
                    false,

                'reference' =>
                    null,
            ];
        }

        $matched =
            $this->matchServiceReference(
                $currentMessage,
                $services
            );

        /*
         * Nếu "Giá bao nhiêu?"
         * không nhắc lại tên service,
         * dùng source được xếp hạng đầu
         * của câu trả lời trước.
         */
        $service =
            $matched
            ?? $services->first();

        if (!$service) {
            return [
                'query' =>
                    $currentMessage,

                'uses_history' =>
                    false,

                'reference' =>
                    null,
            ];
        }

        return [
            'query' =>
                implode(
                    "\n",
                    [
                        'Dịch vụ đang được nhắc tới: '
                            . $service['title']
                            . '.',

                        'Câu hỏi hiện tại: '
                            . $currentMessage,
                    ]
                ),

            'uses_history' =>
                true,

            'reference' =>
                $service,
        ];
    }


    private function latestServiceReferences(
        Collection $history
    ): Collection {
        foreach (
            $history
                ->reverse()
                ->values()
            as $message
        ) {
            if (
                $message->role
                !==
                ChatMessage::ROLE_ASSISTANT
            ) {
                continue;
            }

            $metadata =
                $this->metadataArray(
                    $message->metadata
                );

            $sources =
                $metadata['sources']
                ?? [];

            if (!is_array($sources)) {
                continue;
            }

            $services =
                collect(
                    $sources
                )
                    ->filter(
                        function ($source) {
                            if (!is_array($source)) {
                                return false;
                            }

                            $sourceType =
                                strtoupper(
                                    (string)
                                    (
                                        $source[
                                            'source_type'
                                        ]
                                        ?? ''
                                    )
                                );

                            $type =
                                strtoupper(
                                    (string)
                                    (
                                        $source['type']
                                        ?? ''
                                    )
                                );

                            return
                                $sourceType === 'SERVICE'
                                ||
                                in_array(
                                    $type,
                                    [
                                        'INITIAL_INSPECTION',
                                        'MAINTENANCE_RECOMMENDATION',
                                    ],
                                    true
                                );
                        }
                    )
                    ->map(
                        function ($source) {
                            return [
                                'id' =>
                                    $source[
                                        'source_id'
                                    ]
                                    ??
                                    $source[
                                        'service_id'
                                    ]
                                    ??
                                    null,

                                'title' =>
                                    $source[
                                        'title'
                                    ]
                                    ?? null,
                            ];
                        }
                    )
                    ->filter(
                        fn ($source) =>
                            filled(
                                $source['title']
                            )
                    )
                    ->unique('title')
                    ->values();

            if ($services->isNotEmpty()) {
                return $services;
            }
        }

        return collect();
    }


    private function matchServiceReference(
        string $message,
        Collection $services
    ): ?array {
        $question =
            $this->normalize(
                $message
            );

        $ranked =
            $services
                ->map(
                    function (
                        array $service
                    ) use (
                        $question
                    ) {
                        $title =
                            $this->normalize(
                                $service[
                                    'title'
                                ]
                                ?? ''
                            );

                        $score = 0;

                        if (
                            $title !== ''
                            &&
                            Str::contains(
                                $question,
                                $title
                            )
                        ) {
                            $score += 100;
                        }

                        $tokens =
                            preg_split(
                                '/\s+/',
                                $title,
                                -1,
                                PREG_SPLIT_NO_EMPTY
                            )
                            ?: [];

                        $stopWords = [
                            'dich',
                            'vu',
                            'kiem',
                            'tra',
                            'bao',
                            'duong',
                        ];

                        foreach (
                            $tokens
                            as $token
                        ) {
                            if (
                                strlen($token) >= 3
                                &&
                                !in_array(
                                    $token,
                                    $stopWords,
                                    true
                                )
                                &&
                                Str::contains(
                                    $question,
                                    $token
                                )
                            ) {
                                $score += 10;
                            }
                        }

                        return [
                            'service' =>
                                $service,

                            'score' =>
                                $score,
                        ];
                    }
                )
                ->sortByDesc(
                    'score'
                )
                ->values();

        $best =
            $ranked->first();

        if (
            !$best
            ||
            $best['score'] <= 0
        ) {
            return null;
        }

        return $best['service'];
    }


    private function isKnowledgeFollowUp(
        string $message
    ): bool {
        $message =
            $this->normalize(
                $message
            );

        return $this->containsAny(
            $message,
            [
                'gia bao nhieu',
                'bao nhieu tien',
                'chi phi bao nhieu',
                'mat bao nhieu',
                'mat bao lau',
                'bao lau',
                'chu ky bao lau',
                'khi nao can',
                'dich vu do',
                'cai do',
                'cai nay',
                'the con cai do',
                'con dich vu do',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIGUITY / FOLLOW-UP HELPERS
    |--------------------------------------------------------------------------
    */

    private function containsFollowUpSignal(
        string $message
    ): bool {
        return $this->containsAny(
            $message,
            [
                'no',
                'the',
                'vay',
                'con',
                'neu chua',
                'xe do',
                'xe nay',
                'chiec do',
                'chiec nay',
                'cai do',
                'cai nay',
            ]
        );
    }


    private function metadataArray(
        mixed $metadata
    ): array {
        if (is_array($metadata)) {
            return $metadata;
        }

        if (is_string($metadata)) {
            $decoded =
                json_decode(
                    $metadata,
                    true
                );

            return is_array($decoded)
                ? $decoded
                : [];
        }

        return [];
    }


    private function vehicleName(
        Vehicle $vehicle
    ): string {
        $name =
            trim(
                ($vehicle
                    ->brand
                    ?->name ?? '')
                . ' '
                . ($vehicle
                    ->vehicleModel
                    ?->name ?? '')
            );

        return $name !== ''
            ? $name
            : 'Phương tiện';
    }


    private function sanitizeContent(
        ?string $content
    ): string {
        $content =
            trim(
                (string) $content
            );

        if ($content === '') {
            return '';
        }

        $maxChars =
            max(
                300,
                (int) config(
                    'ai.conversation.max_message_chars',
                    1800
                )
            );

        return Str::limit(
            $content,
            $maxChars,
            '...'
        );
    }


    private function normalize(
        string $text
    ): string {
        $text =
            Str::ascii(
                Str::lower(
                    trim($text)
                )
            );

        $text =
            preg_replace(
                '/[^a-z0-9]+/',
                ' ',
                $text
            );

        return trim(
            preg_replace(
                '/\s+/',
                ' ',
                $text ?? ''
            )
            ?? ''
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