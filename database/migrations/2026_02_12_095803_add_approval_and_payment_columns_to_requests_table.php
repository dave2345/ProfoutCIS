<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->timestamp('paid_at')->nullable()->after('updated_at');
            $table->timestamp('first_approved_at')->nullable()->after('paid_at');
            $table->timestamp('second_approved_at')->nullable()->after('first_approved_at');
            $table->timestamp('third_approved_at')->nullable()->after('second_approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn([
                'paid_at',
                'first_approved_at',
                'second_approved_at',
                'third_approved_at',
            ]);
        });
    }
};
