<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Move KKM from classroom_subject_teacher to subjects table.
     */
    public function up(): void
    {
        // 1. Add kkm to subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedTinyInteger('kkm')->default(75)->after('type')
                  ->comment('Nilai minimum kelulusan untuk mata pelajaran ini');
        });

        // 2. Drop kkm from classroom_subject_teacher
        Schema::table('classroom_subject_teacher', function (Blueprint $table) {
            if (Schema::hasColumn('classroom_subject_teacher', 'kkm')) {
                $table->dropColumn('kkm');
            }
        });
    }

    public function down(): void
    {
        // 1. Restore kkm to classroom_subject_teacher
        Schema::table('classroom_subject_teacher', function (Blueprint $table) {
            $table->unsignedTinyInteger('kkm')->default(75)->after('academic_year_id');
        });

        // 2. Drop kkm from subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn('kkm');
        });
    }
};
