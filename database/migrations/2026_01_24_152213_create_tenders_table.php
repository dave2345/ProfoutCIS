<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tender_number')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['national', 'international', 'government', 'private'])->default('national');
            $table->enum('category', ['construction', 'supply', 'services', 'consultancy', 'other'])->default('services');
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->decimal('bid_bond_amount', 15, 2)->nullable();
            $table->date('published_date')->nullable();
            $table->date('submission_deadline');
            $table->date('opening_date')->nullable();
            $table->date('award_date')->nullable();
            $table->enum('status', ['draft', 'published', 'open', 'closed', 'evaluating', 'awarded', 'cancelled'])->default('draft');
            $table->string('issuing_authority')->nullable();
            $table->string('location')->nullable();
            $table->json('eligibility_criteria')->nullable();
            $table->json('required_documents')->nullable();
            $table->json('evaluation_criteria')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_pre_qualification')->default(false);
            $table->boolean('is_eoi')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'submission_deadline']);
            $table->index(['type', 'category']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
