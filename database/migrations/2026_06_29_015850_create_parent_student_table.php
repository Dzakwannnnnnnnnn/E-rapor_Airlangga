<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parent_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['parent_id', 'student_id']);
        });

        // Migrate existing parent_id data from students table to parent_student pivot table
        if (Schema::hasColumn('students', 'parent_id')) {
            $relations = DB::table('students')->whereNotNull('parent_id')->get();
            foreach ($relations as $relation) {
                DB::table('parent_student')->insert([
                    'parent_id'  => $relation->parent_id,
                    'student_id' => $relation->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Drop foreign key and column from students
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            });
        }
    }

    public function down(): void
    {
        // Re-add parent_id column to students table
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('parent_id')
                  ->nullable()
                  ->after('classroom_id')
                  ->constrained('parents')
                  ->nullOnDelete();
        });

        // Restore data back to parent_id in students
        $relations = DB::table('parent_student')->orderBy('id')->get();
        foreach ($relations as $relation) {
            DB::table('students')
                ->where('id', $relation->student_id)
                ->whereNull('parent_id') // only set the first parent if multiple exist
                ->update(['parent_id' => $relation->parent_id]);
        }

        Schema::dropIfExists('parent_student');
    }
};
