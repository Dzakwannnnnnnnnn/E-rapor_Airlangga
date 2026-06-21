<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add KKM (Kriteria Ketuntasan Minimal) to classroom_subject_teacher.
     * Each teacher can set their own KKM per subject-class assignment.
     */
    public function up(): void
    {
        Schema::table('classroom_subject_teacher', function (Blueprint $table) {
            $table->unsignedTinyInteger('kkm')->default(75)->after('academic_year_id')
                  ->comment('Nilai minimum kelulusan yang ditetapkan guru untuk mapel ini');
        });
    }

    public function down(): void
    {
        Schema::table('classroom_subject_teacher', function (Blueprint $table) {
            $table->dropColumn('kkm');
        });
    }
};
