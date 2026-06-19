<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class StudentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first();
        $classroom    = Classroom::where('name', 'XI PPLG')->first();

        if (!$academicYear || !$classroom) {
            return;
        }

        // 1. Seed Student: Ilham
        $ilham = Student::create([
            'nisn'         => '0071234561',
            'name'         => 'Ilham',
            'classroom_id' => $classroom->id,
        ]);

        Attendance::create([
            'student_id'       => $ilham->id,
            'academic_year_id' => $academicYear->id,
            'hadir'            => 20,
            'sakit'            => 1,
            'izin'             => 0,
            'alpha'            => 0,
        ]);

        ReportCard::create([
            'student_id'       => $ilham->id,
            'academic_year_id' => $academicYear->id,
            'final_score'      => 87.83,
            'rank'             => 1,
            'description'      => 'Pertahankan prestasi yang baik ini dan terus tingkatkan kompetensi pemrograman.',
            'publish_at'       => Carbon::now(),
        ]);

        // 2. Seed Student: Herlambang
        $herlambang = Student::create([
            'nisn'         => '0071234562',
            'name'         => 'Herlambang',
            'classroom_id' => $classroom->id,
        ]);

        Attendance::create([
            'student_id'       => $herlambang->id,
            'academic_year_id' => $academicYear->id,
            'hadir'            => 18,
            'sakit'            => 2,
            'izin'             => 1,
            'alpha'            => 0,
        ]);

        ReportCard::create([
            'student_id'       => $herlambang->id,
            'academic_year_id' => $academicYear->id,
            'final_score'      => 80.00,
            'rank'             => 2,
            'description'      => 'Tingkatkan kedisiplinan belajar di semester berikutnya.',
            'publish_at'       => Carbon::now(),
        ]);

        // 3. Seed Student: Citra
        $citra = Student::create([
            'nisn'         => '0071234563',
            'name'         => 'Citra',
            'classroom_id' => $classroom->id,
        ]);

        Attendance::create([
            'student_id'       => $citra->id,
            'academic_year_id' => $academicYear->id,
            'hadir'            => 22,
            'sakit'            => 0,
            'izin'             => 0,
            'alpha'            => 0,
        ]);

        // 4. Seed Student: Deni
        $deni = Student::create([
            'nisn'         => '0071234564',
            'name'         => 'Deni',
            'classroom_id' => $classroom->id,
        ]);

        Attendance::create([
            'student_id'       => $deni->id,
            'academic_year_id' => $academicYear->id,
            'hadir'            => 19,
            'sakit'            => 1,
            'izin'             => 1,
            'alpha'            => 1,
        ]);

        // 5. Seed Student: Eka
        $eka = Student::create([
            'nisn'         => '0071234565',
            'name'         => 'Eka',
            'classroom_id' => $classroom->id,
        ]);

        Attendance::create([
            'student_id'       => $eka->id,
            'academic_year_id' => $academicYear->id,
            'hadir'            => 21,
            'sakit'            => 0,
            'izin'             => 1,
            'alpha'            => 0,
        ]);
    }
}
