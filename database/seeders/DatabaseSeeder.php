<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ─── Admin ───
        User::create([
            'name'              => 'Admin E-Rapor',
            'email'             => 'admin@test.com',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // ─── Urutan seeder PENTING – jangan diubah ───
        $this->call([
            AcademicYearsSeeder::class,           // 1. Tahun ajaran
            ClassroomSeeder::class,               // 2. Kelas (butuh academic year tidak, tapi dibutuhkan student)
            SubjectSeeder::class,                 // 3. Mata pelajaran
            TeacherSeeder::class,                 // 4. Guru (User + Teacher)
            StudentSeeder::class,                 // 5. Siswa + Parent (User + Parents + Student + Attendance)
            ClassroomSubjectTeacherSeeder::class, // 6. Penugasan guru per kelas per mapel
            AssessmentSeeder::class,              // 7. Assessment template per assignment
            GradeEntrySeeder::class,              // 8. Nilai siswa
        ]);

        // 1. Assign Homeroom Teachers (Wali Kelas) to Classrooms
        $tXpplg = \App\Models\Teacher::whereHas('user', fn($q) => $q->where('email', 'rizki@test.com'))->first();
        $tXdkv = \App\Models\Teacher::whereHas('user', fn($q) => $q->where('email', 'dewi@test.com'))->first();
        $tXipplg = \App\Models\Teacher::whereHas('user', fn($q) => $q->where('email', 'andi@test.com'))->first();
        $tXidkv = \App\Models\Teacher::whereHas('user', fn($q) => $q->where('email', 'larasati@test.com'))->first();

        if ($tXpplg) \App\Models\Classroom::where('name', 'X PPLG')->update(['homeroom_teacher_id' => $tXpplg->id]);
        if ($tXdkv) \App\Models\Classroom::where('name', 'X DKV')->update(['homeroom_teacher_id' => $tXdkv->id]);
        if ($tXipplg) \App\Models\Classroom::where('name', 'XI PPLG')->update(['homeroom_teacher_id' => $tXipplg->id]);
        if ($tXidkv) \App\Models\Classroom::where('name', 'XI DKV')->update(['homeroom_teacher_id' => $tXidkv->id]);

        // 2. Submit all teacher assignments to lock subject scores
        \App\Models\ClassroomSubjectTeacher::query()->update(['is_submitted' => true]);

        // 3. Find the blank student: 'Ilham Ramadhan' to leave blank for simulation
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        $blankStudent = \App\Models\Student::where('name', 'Ilham Ramadhan')->first();

        // 4. Calculate and generate final scores/descriptions in grades table
        $assignments = \App\Models\ClassroomSubjectTeacher::with(['assessments', 'classroom.students'])->get();
        foreach ($assignments as $assignment) {
            $assessments = $assignment->assessments;
            if ($assessments->isEmpty()) continue;

            $students = $assignment->classroom->students;
            foreach ($students as $student) {
                // Skip the blank student
                if ($blankStudent && $student->id === $blankStudent->id) {
                    continue;
                }

                // Compute final score
                $weightedSum = 0;
                $totalWeight = 0;
                foreach ($assessments as $assessment) {
                    $ge = \App\Models\GradeEntry::where('assessment_id', $assessment->id)
                        ->where('student_id', $student->id)
                        ->first();
                    if ($ge && $ge->score !== null) {
                        $weightedSum += (float) $ge->score * (float) $assessment->weight;
                        $totalWeight += (float) $assessment->weight;
                    }
                }

                $final = ($totalWeight > 0) ? round($weightedSum / 100, 2) : null;
                if ($final !== null) {
                    // Generate description
                    if ($final >= 90) {
                        $desc = "Menunjukkan penguasaan yang sangat baik dalam memahami seluruh materi pembelajaran, pengerjaan tugas akademik harian, evaluasi semester, serta sangat terampil dalam menyajikan hasil praktik.";
                    } elseif ($final >= 80) {
                        $desc = "Menunjukkan penguasaan yang baik dalam memahami sebagian besar materi pembelajaran, pengerjaan tugas harian, serta terampil dalam menyelesaikan tugas praktis.";
                    } elseif ($final >= 70) {
                        $desc = "Menunjukkan penguasaan yang cukup dalam memahami materi dasar, pengerjaan tugas harian, serta cukup terampil dalam pengerjaan praktik dasar.";
                    } else {
                        $desc = "Perlu peningkatan dan bimbingan dalam memahami materi pembelajaran dasar dan pengerjaan praktik.";
                    }

                    \App\Models\Grade::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'classroom_subject_teacher_id' => $assignment->id,
                        ],
                        [
                            'final_score' => $final,
                            'description' => $desc,
                        ]
                    );
                }
            }
        }

        // 5. Generate report cards for all students except the blank student
        if ($activeYear) {
            $classrooms = \App\Models\Classroom::with('students')->get();
            foreach ($classrooms as $classroom) {
                $classroomStudents = $classroom->students;
                $studentAverages = [];

                foreach ($classroomStudents as $student) {
                    // Skip the blank student
                    if ($blankStudent && $student->id === $blankStudent->id) {
                        continue;
                    }

                    $studentGrades = \App\Models\Grade::where('student_id', $student->id)->get();
                    if ($studentGrades->isNotEmpty()) {
                        $studentAverages[$student->id] = $studentGrades->avg('final_score');
                    }
                }

                // Sort averages to compute rank
                arsort($studentAverages);

                $rank = 1;
                foreach ($studentAverages as $sId => $avg) {
                    \App\Models\ReportCard::updateOrCreate(
                        [
                            'student_id' => $sId,
                            'academic_year_id' => $activeYear->id,
                        ],
                        [
                            'final_score' => round($avg, 2),
                            'rank' => $rank,
                            'description' => 'Terus pertahankan prestasi akademis Anda, kembangkan keterampilan, dan jadilah pribadi yang berkarakter unggul di masa mendatang.',
                            'is_submitted' => true,
                            'is_validated' => false,
                            'status' => 'pending',
                        ]
                    );
                    $rank++;
                }
            }
        }
    }
}
