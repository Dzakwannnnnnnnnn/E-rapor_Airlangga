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
        Schema::table('report_cards', function (Blueprint $table) {
            // Add unique index on student_id & academic_year_id
            $table->unique(['student_id', 'academic_year_id']);
            
            // Add validation and submission status
            $table->boolean('is_validated')->default(false)->after('publish_at');
            $table->boolean('is_submitted')->default(false)->after('is_validated');
            $table->string('status')->default('draft')->after('is_submitted');
            
            // Make columns nullable to support draft states
            $table->unsignedTinyInteger('rank')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->timestamp('publish_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_cards', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'academic_year_id']);
            $table->dropColumn(['is_validated', 'is_submitted', 'status']);
            
            // Revert columns to not null (assuming values are restored/cleaned up)
            $table->unsignedTinyInteger('rank')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->timestamp('publish_at')->nullable(false)->change();
        });
    }
};
