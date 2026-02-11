<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('original_name');
            $table->string('path');
            $table->string('mime_type');
            $table->bigInteger('size');
            $table->string('extension');
            $table->string('disk')->default('local');
            $table->morphs('documentable');
            $table->enum('category', ['general', 'contract', 'invoice', 'certificate', 'tender', 'project', 'request', 'other'])->default('general');
            $table->json('metadata')->nullable();
            $table->boolean('is_public')->default(false);
            $table->string('password_hash')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['documentable_type']);
            $table->index(['user_id', 'category']);
            $table->index(['created_at', 'is_public']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
