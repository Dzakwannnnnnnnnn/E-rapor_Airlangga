<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\ClassroomSubjectTeacher;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssignmentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $assignmentTitles = [
            'Tugas Harian 1',
            'Tugas Harian 2',
            'Tugas Harian 3',
            'Tugas Praktik 1',
            'Tugas Praktik 2',
        ];

        $allCST = ClassroomSubjectTeacher::with(['classroom.students', 'subject'])->get();

        foreach ($allCST as $cst) {
            $students = $cst->classroom->students;
            if ($students->isEmpty()) continue;

            foreach ($assignmentTitles as $i => $title) {
                $assignment = Assignment::create([
                    'classroom_subject_teacher_id' => $cst->id,
                    'title'    => $title . ' - ' . ($cst->subject->name ?? 'Mapel'),
                    'deadline' => now()->subDays(rand(5, 30)),
                ]);

                foreach ($students as $student) {
                    // ~88% siswa mengumpulkan tugas
                    $submitted = (rand(1, 100) <= 88);
                    AssignmentSubmission::create([
                        'student_id'    => $student->id,
                        'assignment_id' => $assignment->id,
                        'status'        => $submitted ? 'submitted' : 'pending',
                        'submitted_at'  => $submitted ? now()->subDays(rand(1, $i + 2)) : null,
                        'score'         => $submitted ? rand(70, 100) : null,
                    ]);
                }
            }
        }
    }
}
