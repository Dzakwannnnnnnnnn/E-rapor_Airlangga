<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Parents;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $academicYear = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();
        if (!$academicYear) return;

        // ─── Pool nama unik per kelas ───
        $classData = [
            'X PPLG' => [
                'Ilham Kurniawan', 'Herlambang Saputra', 'Riski Firmansyah', 'Adit Nugroho',
                'Zalan Maulana', 'Farel Akbar', 'Yuli Pradana', 'Wildan Hafiz',
                'Dafa Ramadhani', 'Gilang Permana', 'Iskandar Zulkarnain', 'Ferdi Setiawan',
                'Surya Pratama', 'Akbar Ramadhan', 'Rafa Hidayat', 'Milo Santoso',
                'Beri Wicaksono', 'Naila Putri', 'Puput Anggraeni', 'Cika Rahayu',
                'Wanda Oktavia', 'Mela Fitriani', 'Fabi Kurniadi', 'Windut Lestari',
                'Refan Hidayatullah',
            ],
            'X DKV' => [
                'Joseph Alexandr', 'Rifa Aulia', 'Kenu Bahtiar', 'Busu Ramlan',
                'Lafi Santosa', 'Frinki Irawan', 'Frunku Mardani', 'Frenke Situmorang',
                'Fronko Siagian', 'Abi Pratama', 'Kasep Nugraha', 'Asep Gunawan',
                'Sarpen Wijaya', 'Nopal Riyanto', 'Wiki Supriyadi', 'Weke Ramdani',
                'Woko Susanto', 'Kurniadu Prasetyo', 'Winduy Fatimah', 'Waka Ramadhan',
                'Jofar Hakim', 'Dedi Suryanto', 'Mamat Setiawan',
            ],
            'XI PPLG' => [
                'Kurniawan Budi', 'Kurniadi Prayoga', 'Gilang Ramadan', 'Ferdi Purnomo',
                'Riski Amelia', 'Adit Saputra', 'Dedi Wahyudi', 'Zalan Pratama',
                'Yuli Anggraeni', 'Wildan Ramadhan', 'Dafa Nugroho', 'Naila Rahma',
                'Puput Sari', 'Cika Agustina', 'Fabi Nurhaliza', 'Windut Rahayu',
                'Refan Santoso', 'Wanda Permata', 'Mela Sartika', 'Akbar Wahyudi',
                'Rafa Setiawan', 'Surya Gunawan', 'Milo Pratama', 'Beri Kurniawan',
                'Farel Maulana', 'Iskandar Prabowo', 'Ilham Ramadhan',
            ],
            'XI DKV' => [
                'Asep Sunarya', 'Kasep Pradipta', 'Nopal Sanjaya', 'Sarpen Abdillah',
                'Joseph Marpaung', 'Rifa Maharani', 'Kenu Syahputra', 'Busu Firmansyah',
                'Lafi Mubarok', 'Frinki Hasan', 'Frunku Lubis', 'Frenke Pangaribuan',
                'Fronko Hutagalung', 'Abi Saputra', 'Jofar Marbun', 'Mamat Harahap',
                'Wiki Pardosi', 'Weke Tampubolon', 'Woko Sinaga', 'Waka Sitorus',
                'Winduy Siregar', 'Kurniadu Nasution', 'Dedi Manurung', 'Wanda Sagala',
            ],
        ];

        $relations   = ['ayah', 'ibu', 'wali'];
        $nisnCounter = 1000001;
        $emailIdx    = 1;

        foreach ($classData as $className => $names) {
            $classroom = Classroom::where('name', $className)->first();
            if (!$classroom) continue;

            foreach ($names as $i => $name) {
                $firstName = explode(' ', $name)[0];

                // 1. Buat akun User untuk parent
                $parentUser = User::create([
                    'name'              => 'Wali ' . $firstName,
                    'email'             => 'wali' . $emailIdx . '@test.com',
                    'password'          => Hash::make('password'),
                    'role'              => 'parent',
                    'email_verified_at' => now(),
                ]);

                // 2. Buat record Parents
                $parent = Parents::create([
                    'user_id'  => $parentUser->id,
                    'relation' => $relations[$i % 3],
                    'telp'     => '08' . rand(100000000, 999999999),
                ]);

                // 3. Buat Student (tanpa parent_id, relasi lewat pivot)
                $student = Student::create([
                    'nisn'         => (string) $nisnCounter,
                    'name'         => $name,
                    'classroom_id' => $classroom->id,
                ]);

                // 4. Hubungkan siswa ke orang tua via pivot parent_student
                $parent->students()->attach($student->id);

                // 5. Absensi
                Attendance::create([
                    'student_id'       => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'hadir'            => rand(18, 24),
                    'sakit'            => rand(0, 2),
                    'izin'             => rand(0, 2),
                    'alpha'            => rand(0, 1),
                ]);

                $nisnCounter++;
                $emailIdx++;
            }
        }
    }
}
