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
    }
}
