<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus dulu tabel yang punya FK ke assignments
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
    }

    public function down(): void
    {
        // Kembalikan jika rollback (disederhanakan, tanpa data)
        Schema::create('assignments', function ($table) {
            $table->id();
            $table->foreignId('classroom_subject_teacher_id')
                ->constrained('classroom_subject_teacher')
                ->cascadeOnDelete();
            $table->string('title');
            $table->dateTime('deadline')->nullable();
            $table->timestamps();
        });

        Schema::create('assignment_submissions', function ($table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->enum('status', ['pending', 'submitted'])->default('pending');
            $table->dateTime('submitted_at')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'assignment_id']);
        });
    }
};
