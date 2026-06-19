<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\ClassroomSubjectTeacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AssessmentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed assessments for each classroom_subject_teacher assignment.
     * Template: UH 1-3 (bobot 37.5% total), Tugas 1-3 (25% total), PAS (37.5%)
     */
    public function run(): void
    {
        $assignments = ClassroomSubjectTeacher::all();

        foreach ($assignments as $assignment) {
            // UH 1, 2, 3 — each weighs 12.5% (total 37.5%)
            foreach (range(1, 3) as $i) {
                Assessment::create([
                    'classroom_subject_teacher_id' => $assignment->id,
                    'type'        => 'uh',
                    'name'        => "UH $i",
                    'date'        => Carbon::now()->subDays(rand(10, 60))->toDateString(),
                    'weight'      => 12.50,
                    'description' => "Ulangan Harian ke-$i",
                    'sequence'    => $i,
                ]);
            }

            // Tugas 1, 2, 3 — each weighs 8.33% (total ~25%)
            foreach (range(1, 3) as $i) {
                Assessment::create([
                    'classroom_subject_teacher_id' => $assignment->id,
                    'type'        => 'tugas',
                    'name'        => "Tugas $i",
                    'date'        => Carbon::now()->subDays(rand(5, 40))->toDateString(),
                    'weight'      => 8.33,
                    'description' => "Tugas ke-$i",
                    'sequence'    => $i,
                ]);
            }

            // PAS — weighs 37.5%
            Assessment::create([
                'classroom_subject_teacher_id' => $assignment->id,
                'type'        => 'pas',
                'name'        => 'PAS Semester 1',
                'date'        => Carbon::now()->subDays(rand(1, 7))->toDateString(),
                'weight'      => 37.50,
                'description' => 'Penilaian Akhir Semester',
                'sequence'    => 1,
            ]);
        }
    }
}
