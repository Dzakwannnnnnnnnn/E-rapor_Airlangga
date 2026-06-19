<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * 1 guru = 1 mata pelajaran di semua kelas.
     * Mata pelajaran shared (7) + 2 ekskul = 9 guru.
     * PPLG Kejuruan & Mapil = 2 guru khusus PPLG.
     * DKV Kejuruan & Mapil  = 2 guru khusus DKV.
     * Total = 13 guru.
     */
    public function run(): void
    {
        $teachers = [
            // Shared (mengajar di semua 4 kelas)
            ['name' => 'Bpk. Sudarsono, S.Pd.',      'email' => 'sudarsono@test.com',   'nip' => '1978010120050001', 'gender' => 'L', 'telp' => '081234000001'],
            ['name' => 'Ibu Rahmawati, S.Pd.',        'email' => 'rahmawati@test.com',   'nip' => '1979051520060002', 'gender' => 'P', 'telp' => '081234000002'],
            ['name' => 'Bpk. Firmansyah, S.Pd.',      'email' => 'firmansyah@test.com',  'nip' => '1980032020070003', 'gender' => 'L', 'telp' => '081234000003'],
            ['name' => 'Ibu Nurlaila, S.Pd.',          'email' => 'nurlaila@test.com',    'nip' => '1982110520080004', 'gender' => 'P', 'telp' => '081234000004'],
            ['name' => 'Bpk. Hendra Susilo, M.Pd.',   'email' => 'hendra@test.com',      'nip' => '1976121020040005', 'gender' => 'L', 'telp' => '081234000005'],
            ['name' => 'Ibu Siti Maryam, S.Pd.',       'email' => 'siti@test.com',        'nip' => '1985071820090006', 'gender' => 'P', 'telp' => '081234000006'],
            ['name' => 'Bpk. Wahyudi, S.E., M.Pd.',   'email' => 'wahyudi@test.com',     'nip' => '1979082520060007', 'gender' => 'L', 'telp' => '081234000007'],

            // PPLG – Kejuruan & Mapil
            ['name' => 'Bpk. Rizki Pratama, S.Kom.',  'email' => 'rizki@test.com',       'nip' => '1990011520150008', 'gender' => 'L', 'telp' => '081234000008'],
            ['name' => 'Bpk. Andi Setiawan, S.Kom.',  'email' => 'andi@test.com',        'nip' => '1988122020140009', 'gender' => 'L', 'telp' => '081234000009'],

            // DKV – Kejuruan & Mapil
            ['name' => 'Ibu Dewi Kartika, S.Ds.',      'email' => 'dewi@test.com',        'nip' => '1992031020160010', 'gender' => 'P', 'telp' => '081234000010'],
            ['name' => 'Ibu Larasati, S.Sn.',           'email' => 'larasati@test.com',    'nip' => '1991072520150011', 'gender' => 'P', 'telp' => '081234000011'],

            // Ekskul
            ['name' => 'Bpk. Fadlan Maulana, S.Kom.', 'email' => 'fadlan@test.com',      'nip' => '1993051220180012', 'gender' => 'L', 'telp' => '081234000012'],
            ['name' => 'Bpk. Dimas Arifin, S.Sn.',    'email' => 'dimas@test.com',       'nip' => '1994081820190013', 'gender' => 'L', 'telp' => '081234000013'],
        ];

        foreach ($teachers as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => Hash::make('password'),
                'role'              => 'teacher',
                'email_verified_at' => now(),
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip'     => $data['nip'],
                'gender'  => $data['gender'],
                'telp'    => $data['telp'],
            ]);
        }
    }
}
