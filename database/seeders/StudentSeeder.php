<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();
        $classroom = Classroom::where('name', 'XI PPLG')->first();

        $subjectPplg = Subject::where('name', 'Dasar Dasar PPLG')->first();
        $subjectMath = Subject::where('name', 'Matematika')->first();
        $subjectPpkn = Subject::where('name', 'Pendidikan Pancasila Dan Kewarganegaraan (PPKN)')->first();

        $teacherPplgMath = Teacher::whereHas('user', function ($query) {
            $query->where('name', 'Guru Penguji PPLG');
        })->first();

        $teacherPpkn = Teacher::whereHas('user', function ($query) {
            $query->where('name', 'Guru Penguji TJKT');
        })->first();

        // 1. Seed Student: Ilham
        $ilham = Student::create([
            'nisn' => '0071234561',
            'name' => 'Ilham',
            'classroom_id' => $classroom->id,
        ]);

        Grade::create([
            'student_id' => $ilham->id,
            'subject_id' => $subjectPplg->id,
            'teacher_id' => $teacherPplgMath->id,
            'academic_year_id' => $academicYear->id,
            'final_score' => 88.50,
            'description' => 'Sangat baik dalam memahami materi routing dan database integration.',
        ]);

        Grade::create([
            'student_id' => $ilham->id,
            'subject_id' => $subjectMath->id,
            'teacher_id' => $teacherPplgMath->id,
            'academic_year_id' => $academicYear->id,
            'final_score' => 85.00,
            'description' => 'Baik dalam memahami konsep limit dan turunan.',
        ]);

        Grade::create([
            'student_id' => $ilham->id,
            'subject_id' => $subjectPpkn->id,
            'teacher_id' => $teacherPpkn->id,
            'academic_year_id' => $academicYear->id,
            'final_score' => 90.00,
            'description' => 'Menunjukkan pemahaman yang sangat mendalam mengenai nilai-nilai PPKN.',
        ]);

        Attendance::create([
            'student_id' => $ilham->id,
            'academic_year_id' => $academicYear->id,
            'hadir' => 20,
            'sakit' => 1,
            'izin' => 0,
            'alpha' => 0,
        ]);

        ReportCard::create([
            'student_id' => $ilham->id,
            'academic_year_id' => $academicYear->id,
            'final_score' => 87.83,
            'rank' => 1,
            'description' => 'Pertahankan prestasi yang baik ini dan terus tingkatkan kompetensi pemrograman.',
            'publish_at' => Carbon::now(),
        ]);

        // 2. Seed Student: Herlambang
        $herlambang = Student::create([
            'nisn' => '0071234562',
            'name' => 'Herlambang',
            'classroom_id' => $classroom->id,
        ]);

        Grade::create([
            'student_id' => $herlambang->id,
            'subject_id' => $subjectPplg->id,
            'teacher_id' => $teacherPplgMath->id,
            'academic_year_id' => $academicYear->id,
            'final_score' => 78.00,
            'description' => 'Cukup memahami dasar-dasar pemrograman web, perlu banyak berlatih logic.',
        ]);

        Grade::create([
            'student_id' => $herlambang->id,
            'subject_id' => $subjectMath->id,
            'teacher_id' => $teacherPplgMath->id,
            'academic_year_id' => $academicYear->id,
            'final_score' => 80.00,
            'description' => 'Memahami operasi matriks dengan baik.',
        ]);

        Grade::create([
            'student_id' => $herlambang->id,
            'subject_id' => $subjectPpkn->id,
            'teacher_id' => $teacherPpkn->id,
            'academic_year_id' => $academicYear->id,
            'final_score' => 82.00,
            'description' => 'Berperilaku baik dan disiplin dalam mengikuti pembelajaran.',
        ]);

        Attendance::create([
            'student_id' => $herlambang->id,
            'academic_year_id' => $academicYear->id,
            'hadir' => 18,
            'sakit' => 2,
            'izin' => 1,
            'alpha' => 0,
        ]);

        ReportCard::create([
            'student_id' => $herlambang->id,
            'academic_year_id' => $academicYear->id,
            'final_score' => 80.00,
            'rank' => 2,
            'description' => 'Tingkatkan kedisiplinan belajar di semester berikutnya.',
            'publish_at' => Carbon::now(),
        ]);
    }
}

