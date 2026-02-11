<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_workflow_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // submitted, approved, rejected, returned, escalated, etc.
            $table->enum('stage', ['junior', 'senior', 'manager', 'payment', 'other'])->nullable();
            $table->text('description');
            $table->json('metadata')->nullable(); // Stores old/new values, etc.
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['request_id', 'created_at']);
            $table->index(['user_id', 'action']);
            $table->index(['stage', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_workflow_histories');
    }
};
