<?php

namespace App\Console\Commands;

use App\Models\KnowledgeDocument;
use App\Services\GeminiEmbeddingService;
use Illuminate\Console\Command;
use Throwable;

class GenerateKnowledgeEmbeddings extends Command
{
    protected $signature =
        'knowledge:embed
        {--force : Regenerate all embeddings}';

    protected $description =
        'Generate Gemini retrieval embeddings for AutoCare knowledge documents';


    public function handle(
        GeminiEmbeddingService $embeddingService
    ): int {
        if (!$embeddingService->isConfigured()) {
            $this->error(
                'Gemini Embedding chưa được cấu hình.'
            );

            return self::FAILURE;
        }

        $query =
            KnowledgeDocument::query()
                ->where(
                    'is_active',
                    true
                )
                ->orderBy('id');

        if (!$this->option('force')) {
            $query->whereNull(
                'embedding'
            );
        }

        $documents =
            $query->get();

        if ($documents->isEmpty()) {
            $this->info(
                'Không có Knowledge Document nào cần tạo embedding.'
            );

            return self::SUCCESS;
        }

        $this->info(
            'Bắt đầu tạo retrieval embedding cho '
            . $documents->count()
            . ' Knowledge Document...'
        );

        $bar =
            $this->output
                ->createProgressBar(
                    $documents->count()
                );

        $bar->start();

        $successCount = 0;
        $failureCount = 0;

        foreach ($documents as $document) {
            try {
                /*
                 * QUAN TRỌNG:
                 *
                 * Document phải dùng:
                 *
                 * title: {title} | text: {content}
                 *
                 * Không dùng cùng format với query.
                 */
                $embedding =
                    $embeddingService
                        ->embedDocument(
                            $document->title,
                            $document->content
                        );

                $document->embedding =
                    $embedding;

                $metadata =
                    is_array(
                        $document->metadata
                    )
                        ? $document->metadata
                        : [];

                $metadata[
                    'embedding'
                ] = [
                    'provider' =>
                        'gemini',

                    'model' =>
                        config(
                            'ai.embedding.model'
                        ),

                    'dimensions' =>
                        count(
                            $embedding
                        ),

                    'usage' =>
                        'retrieval_document',

                    'format' =>
                        'title_text',

                    'generated_at' =>
                        now()
                            ->toIso8601String(),
                ];

                $document->metadata =
                    $metadata;

                $document->save();

                $successCount++;
            } catch (Throwable $exception) {
                report($exception);

                $failureCount++;

                $this->newLine();

                $this->warn(
                    'Không thể tạo embedding cho document #'
                    . $document->id
                    . ' - '
                    . $document->title
                    . ': '
                    . $exception->getMessage()
                );
            }

            $bar->advance();

            usleep(
                200000
            );
        }

        $bar->finish();

        $this->newLine(2);

        $this->info(
            "Thành công: {$successCount}"
        );

        if ($failureCount > 0) {
            $this->warn(
                "Thất bại: {$failureCount}"
            );
        } else {
            $this->info(
                'Tất cả Knowledge Document đã có retrieval embedding.'
            );
        }

        return $failureCount === 0
            ? self::SUCCESS
            : self::FAILURE;
    }
}