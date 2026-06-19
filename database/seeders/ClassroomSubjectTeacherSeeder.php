<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomSubjectTeacher;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSubjectTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();
        if (!$academicYear) {
            $academicYear = AcademicYear::first();
        }

        $classPplg = Classroom::where('name', 'XI PPLG')->first();
        $classTjkt = Classroom::where('name', 'XI TJKT')->first();

        $subjectPplg = Subject::where('name', 'Dasar Dasar PPLG')->first();
        $subjectMath = Subject::where('name', 'Matematika')->first();
        $subjectPpkn = Subject::where('name', 'Pendidikan Pancasila Dan Kewarganegaraan (PPKN)')->first();

        $teacherPplgMath = Teacher::whereHas('user', function ($query) {
            $query->where('name', 'Guru Penguji PPLG');
        })->first();

        $teacherPpkn = Teacher::whereHas('user', function ($query) {
            $query->where('name', 'Guru Penguji TJKT');
        })->first();

        // Check if all necessary entities exist before seeding
        if ($academicYear && $classPplg && $classTjkt && $subjectPplg && $subjectMath && $subjectPpkn && $teacherPplgMath && $teacherPpkn) {
            // Guru Penguji PPLG teaches:
            // 1. Dasar Dasar PPLG in XI PPLG
            ClassroomSubjectTeacher::create([
                'classroom_id' => $classPplg->id,
                'subject_id' => $subjectPplg->id,
                'teacher_id' => $teacherPplgMath->id,
                'academic_year_id' => $academicYear->id,
            ]);

            // 2. Matematika in XI PPLG
            ClassroomSubjectTeacher::create([
                'classroom_id' => $classPplg->id,
                'subject_id' => $subjectMath->id,
                'teacher_id' => $teacherPplgMath->id,
                'academic_year_id' => $academicYear->id,
            ]);

            // Guru Penguji TJKT teaches:
            // 1. PPKN in XI PPLG
            ClassroomSubjectTeacher::create([
                'classroom_id' => $classPplg->id,
                'subject_id' => $subjectPpkn->id,
                'teacher_id' => $teacherPpkn->id,
                'academic_year_id' => $academicYear->id,
            ]);

            // 2. Matematika in XI TJKT
            ClassroomSubjectTeacher::create([
                'classroom_id' => $classTjkt->id,
                'subject_id' => $subjectMath->id,
                'teacher_id' => $teacherPpkn->id,
                'academic_year_id' => $academicYear->id,
            ]);
        }
    }
}
