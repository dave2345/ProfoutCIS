<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('budget_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('total_amount', 15, 2);
            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->decimal('committed_amount', 15, 2)->default(0);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->decimal('available_amount', 15, 2)->virtualAs('total_amount - allocated_amount - committed_amount - spent_amount');
            $table->string('currency', 3)->default('USD');
            $table->enum('type', ['departmental', 'project', 'capital', 'operational', 'contingency', 'other'])->default('departmental');
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'active', 'closed', 'frozen', 'archived'])->default('draft');
            $table->foreignId('created_by_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('categories')->nullable(); // Budget line items
            $table->json('restrictions')->nullable(); // Spending restrictions
            $table->integer('version')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['budget_code', 'status']);
            $table->index(['type', 'status']);
            $table->index(['project_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_allocations');
    }
};
