<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Requester
            $table->string('request_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->enum('type', [
                'payment_request',
                'purchase_request',
                'travel_request',
                'leave_request',
                'advance_request',
                'expense_claim',
                'service_request',
                'equipment_request',
                'other'
            ])->default('payment_request');

            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            // Status reflects the overall workflow
            $table->enum('status', [
                'draft',          // Initial draft
                'submitted',      // Submitted for junior approval
                'junior_approved', // Approved by junior account
                'junior_rejected', // Rejected by junior account
                'senior_approved', // Approved by senior account
                'senior_rejected', // Rejected by senior account
                'manager_approved', // Approved by manager/CEO
                'manager_rejected', // Rejected by manager/CEO
                'payment_processing', // Sent for payment processing
                'paid',           // Payment completed
                'cancelled',      // Request cancelled
                'on_hold' ,        // Put on hold
                'rejected'        // Generic rejected status (if needed)
            ])->default('draft');

            // Financial details
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->enum('payment_method', [
                'bank_transfer',
                'check',
                'cash',
                'credit_card',
                'debit_card',
                'mobile_money',
                'other'
            ])->nullable();

            // Bank details for payment
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('iban')->nullable();

            // Dates
            $table->date('request_date')->nullable();
            $table->date('required_by_date')->nullable();
            $table->date('payment_date')->nullable();

            // Related entities
            $table->foreignId('related_project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('related_tender_id')->nullable()->constrained('tenders')->onDelete('set null');


            // Approval tracking
            $table->foreignId('junior_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('junior_approval_at')->nullable();
            $table->text('junior_approval_notes')->nullable();

            $table->foreignId('senior_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('senior_approval_at')->nullable();
            $table->text('senior_approval_notes')->nullable();

            $table->foreignId('manager_approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('manager_approval_at')->nullable();
            $table->text('manager_approval_notes')->nullable();

            // Payment tracking
            $table->foreignId('processed_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('payment_processed_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('payment_notes')->nullable();

            // Supporting documents
            $table->json('supporting_documents')->nullable();
            $table->json('invoices')->nullable();
            $table->json('quotes')->nullable();

            // Categories and tags
            $table->enum('category', [
                'operational',
                'capital',
                'personnel',
                'travel',
                'supplies',
                'services',
                'utilities',
                'maintenance',
                'other'
            ])->default('operational');

            $table->json('tags')->nullable();
            $table->json('line_items')->nullable(); // For multiple items in request

            // Budget tracking
            $table->decimal('budget_allocated', 15, 2)->nullable();
            $table->decimal('budget_remaining', 15, 2)->nullable();
            $table->string('budget_code')->nullable();

            // Additional fields
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_pattern')->nullable(); // daily, weekly, monthly, etc.
            $table->date('next_recurrence_date')->nullable();
            $table->integer('sla_days')->default(14); // Service Level Agreement days
            $table->text('rejection_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('notes')->nullable();

            // Audit trail
            $table->integer('revision_number')->default(1);
            $table->foreignId('previous_version_id')->nullable()->constrained('requests')->onDelete('set null');
            $table->boolean('is_latest_version')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['status', 'priority']);
            $table->index(['user_id', 'status']);
            $table->index(['type', 'required_by_date']);
            $table->index(['junior_approver_id', 'status']);
            $table->index(['senior_approver_id', 'status']);
            $table->index(['manager_approver_id', 'status']);
            $table->index('request_number');
            $table->index(['created_at', 'status']);
            $table->index('amount');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
