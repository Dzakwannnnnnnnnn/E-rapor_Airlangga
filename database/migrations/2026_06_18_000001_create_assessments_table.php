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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_subject_teacher_id')->constrained('classroom_subject_teacher')->cascadeOnDelete();
            $table->enum('type', ['uh', 'tugas', 'pas']); // uh = ulangan harian, tugas, pas
            $table->string('name');               // e.g. "UH 1", "Tugas 1", "PAS Semester 1"
            $table->date('date')->nullable();
            $table->decimal('weight', 5, 2);      // bobot dalam persen, e.g. 12.50
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('sequence')->default(1); // urutan: UH ke-1, ke-2, ke-3
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
