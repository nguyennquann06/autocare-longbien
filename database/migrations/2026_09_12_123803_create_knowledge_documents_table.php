<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'knowledge_documents',
            function (Blueprint $table) {
                $table->id();

                $table->string('title');

                $table->longText('content');

                $table
                    ->string('source_type', 50)
                    ->nullable();

                $table
                    ->unsignedBigInteger('source_id')
                    ->nullable();

                $table
                    ->longText('embedding')
                    ->nullable();

                $table
                    ->json('metadata')
                    ->nullable();

                $table
                    ->boolean('is_active')
                    ->default(true);

                $table->timestamps();

                $table->index('source_type');

                $table->index('source_id');

                $table->index('is_active');

                $table->index([
                    'source_type',
                    'source_id',
                ]);
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'knowledge_documents'
        );
    }
};