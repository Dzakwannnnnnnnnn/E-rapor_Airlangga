<?php

namespace Database\Seeders;

use App\Models\Parents;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ParentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'Wali Murid Ilham',
            'email' => 'wali@test.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
        ]);

        Parents::create([
            'user_id' => $user1->id,
            'name' => 'Wali Murid Ilham',
        ]);

        $user2 = User::create([
            'name' => 'Wali Murid Herlambang',
            'email' => 'wali2@test.com',
            'password' => Hash::make('password'),
            'role' => 'parent',
        ]);

        Parents::create([
            'user_id' => $user2->id,
            'name' => 'Wali Murid Herlambang',
        ]);
    }
}

