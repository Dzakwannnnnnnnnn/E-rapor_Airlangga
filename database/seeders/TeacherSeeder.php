<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Guru Penguji PPLG',
            'email' => 'guru@test.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $user1->id,
            'nip' => '1981020320050110',
            'name' => 'Guru Penguji PPLG',
        ]);

        $user2 = User::create([
            'name' => 'Guru Penguji TJKT',
            'email' => 'guru2@test.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'user_id' => $user2->id,
            'nip' => '1985040520100220',
            'name' => 'Guru Penguji TJKT',
        ]);
    }
}

