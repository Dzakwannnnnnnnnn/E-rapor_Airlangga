<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to update grades and classroom_subject_teacher tables.
     */
    public function up(): void
    {
        // 1. Add description_knowledge and description_skill to grades table
        Schema::table('grades', function (Blueprint $table) {
            $table->text('description_knowledge')->nullable()->after('final_score');
            $table->text('description_skill')->nullable()->after('description_knowledge');
            
            // Drop old description column
            if (Schema::hasColumn('grades', 'description')) {
                $table->dropColumn('description');
            }
        });

        // 2. Add is_submitted to classroom_subject_teacher table
        Schema::table('classroom_subject_teacher', function (Blueprint $table) {
            $table->boolean('is_submitted')->default(false)->after('academic_year_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Restore grades table
        Schema::table('grades', function (Blueprint $table) {
            $table->text('description')->nullable()->after('final_score');
            $table->dropColumn(['description_knowledge', 'description_skill']);
        });

        // 2. Restore classroom_subject_teacher table
        Schema::table('classroom_subject_teacher', function (Blueprint $table) {
            $table->dropColumn('is_submitted');
        });
    }
};
