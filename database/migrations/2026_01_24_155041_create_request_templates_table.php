<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['payment_request', 'purchase_request', 'travel_request', 'leave_request', 'other'])->default('payment_request');
            $table->json('default_fields')->nullable(); // Default form fields and values
            $table->json('required_fields')->nullable(); // Required fields
            $table->json('validation_rules')->nullable(); // Validation rules
            $table->json('approval_workflow')->nullable(); // Default approval workflow
            $table->decimal('default_amount', 15, 2)->nullable();
            $table->string('default_currency', 3)->default('USD');
            $table->enum('category', ['operational', 'capital', 'personnel', 'travel', 'supplies', 'services', 'other'])->default('operational');
            $table->integer('sla_days')->default(14);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_budget_code')->default(false);
            $table->boolean('requires_attachments')->default(false);
            $table->integer('attachment_min_count')->default(0);
            $table->json('allowed_attachment_types')->nullable();
            $table->foreignId('created_by_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['code', 'is_active']);
            $table->index(['type',]);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_templates');
    }
};
