<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->enum('approval_level', ['junior', 'senior', 'manager'])->default('junior');
            $table->enum('status', ['pending', 'approved', 'rejected', 'on_hold', 'reassigned'])->default('pending');
            $table->text('comments')->nullable();
            $table->json('conditions')->nullable(); // Any conditions for approval
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('action_at')->nullable();
            $table->integer('sequence_number'); // Order in approval chain (1, 2, 3)
            $table->boolean('is_mandatory')->default(true);
            $table->integer('escalation_days')->default(2); // Days before escalation
            $table->timestamp('escalated_at')->nullable();
            $table->foreignId('escalated_to_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('attachments')->nullable(); // Supporting docs for approval decision
            $table->timestamps();
            $table->softDeletes();

            $table->index(['request_id', 'approval_level']);
            $table->index(['approver_id', 'status']);
            $table->index(['status', 'action_at']);
            $table->index(['request_id', 'sequence_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_approvals');
    }
};
