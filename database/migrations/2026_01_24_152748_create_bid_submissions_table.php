<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bid_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tender_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('bid_number')->unique();
            $table->decimal('bid_amount', 15, 2);
            $table->decimal('bid_bond_amount', 15, 2)->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'qualified', 'disqualified', 'awarded', 'rejected'])->default('draft');
            $table->date('submission_date');
            $table->json('technical_documents')->nullable();
            $table->json('financial_documents')->nullable();
            $table->json('eligibility_documents')->nullable();
            $table->text('proposal_summary')->nullable();
            $table->text('notes')->nullable();
            $table->json('evaluation_results')->nullable();
            $table->decimal('evaluation_score', 5, 2)->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tender_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('bid_amount');
            $table->index('submission_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bid_submissions');
    }
};
