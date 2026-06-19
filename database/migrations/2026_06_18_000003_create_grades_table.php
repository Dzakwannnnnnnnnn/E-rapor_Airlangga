<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Stores the computed final score per student per classroom_subject_teacher.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_subject_teacher_id')
                ->constrained('classroom_subject_teacher')
                ->cascadeOnDelete();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'classroom_subject_teacher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
