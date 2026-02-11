<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_budget_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->onDelete('cascade');
            $table->foreignId('budget_allocation_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->decimal('committed_amount', 15, 2)->default(0);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->enum('status', ['proposed', 'reserved', 'committed', 'spent', 'released', 'cancelled'])->default('proposed');
            $table->text('notes')->nullable();
            $table->foreignId('created_by_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['request_id', 'budget_allocation_id']);
            $table->index(['budget_allocation_id', 'status']);
            $table->index(['request_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_budget_links');
    }
};
