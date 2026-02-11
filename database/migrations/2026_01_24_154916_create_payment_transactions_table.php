<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained()->onDelete('cascade');
            $table->string('transaction_reference')->unique();
            $table->enum('transaction_type', ['payment', 'refund', 'adjustment', 'reversal'])->default('payment');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'reconciled'])->default('pending');
            $table->enum('payment_method', ['bank_transfer', 'check', 'cash', 'credit_card', 'debit_card', 'mobile_money', 'other'])->default('bank_transfer');
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('iban')->nullable();
            $table->string('check_number')->nullable();
            $table->date('payment_date');
            $table->date('value_date')->nullable(); // Date funds become available
            $table->foreignId('processed_by_id')->constrained('users')->onDelete('cascade');
            $table->text('payment_notes')->nullable();
            $table->json('payment_proof')->nullable(); // Receipts, bank slips, etc.
            $table->json('tax_details')->nullable(); // VAT, withholding tax, etc.
            $table->decimal('tax_amount', 15, 2)->nullable();
            $table->decimal('fee_amount', 15, 2)->nullable();
            $table->decimal('net_amount', 15, 2);
            $table->boolean('is_reconciled')->default(false);
            $table->date('reconciliation_date')->nullable();
            $table->foreignId('reconciled_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reconciliation_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['request_id', 'status']);
            $table->index(['transaction_reference', 'payment_date']);
            $table->index(['payment_method', 'status']);
            $table->index(['processed_by_id', 'created_at']);
            $table->index(['payment_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
