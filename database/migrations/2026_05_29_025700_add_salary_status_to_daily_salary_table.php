<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add salary_status column to track whether salary was auto-calculated,
     * pending manual input, or manually set by admin.
     */
    public function up(): void
    {
        Schema::table('daily_salary', function (Blueprint $table) {
            // 'auto' = system calculated (>=9h), 'pending_manual' = awaiting admin, 'manual' = admin set
            $table->string('salary_status')->default('auto')->after('salary_amount');
            // Make salary_amount nullable so pending entries can have null
            $table->decimal('salary_amount', 12, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('daily_salary', function (Blueprint $table) {
            $table->dropColumn('salary_status');
            $table->decimal('salary_amount', 12, 2)->nullable(false)->change();
        });
    }
};
