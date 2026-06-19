<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\ClassroomSubjectTeacher;
use App\Models\GradeEntry;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeEntrySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed sample grade entries for students.
     */
    public function run(): void
    {
        $assignments = ClassroomSubjectTeacher::with(['assessments', 'classroom.students'])->get();

        foreach ($assignments as $assignment) {
            $students   = $assignment->classroom->students;
            $assessments = $assignment->assessments;

            foreach ($students as $student) {
                foreach ($assessments as $assessment) {
                    // Generate realistic random scores per type
                    $score = match ($assessment->type) {
                        'uh'    => rand(70, 95),
                        'tugas' => rand(75, 100),
                        'pas'   => rand(65, 90),
                        default => rand(70, 95),
                    };

                    GradeEntry::updateOrCreate(
                        [
                            'assessment_id' => $assessment->id,
                            'student_id'    => $student->id,
                        ],
                        [
                            'score'       => $score,
                            'description' => null,
                        ]
                    );
                }
            }
        }
    }
}
