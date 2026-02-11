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
        Schema::create('certificates', function (Blueprint $table) {
             $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('title');
            $table->enum('type', ['compliance', 'accreditation', 'license', 'award', 'training', 'membership', 'other'])->default('compliance');
            $table->enum('status', ['draft', 'active', 'expired', 'revoked', 'renewed'])->default('draft');
            $table->string('issuing_authority');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->string('validity_period')->nullable();
            $table->foreignId('related_project_id')->nullable()->constrained('projects')->onDelete('set null');
            $table->foreignId('related_tender_id')->nullable()->constrained('tenders')->onDelete('set null');
            $table->json('requirements')->nullable();
            $table->json('attachments')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_renewable')->default(false);
            $table->integer('renewal_reminder_days')->default(30);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'status']);
            $table->index(['user_id', 'status']);
            $table->index(['expiry_date', 'status']);
            $table->index(['issuing_authority', 'issue_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
