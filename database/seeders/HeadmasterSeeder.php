<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HeadmasterSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kepsek@smktiairlangga.sch.id'],
            [
                'name'              => 'Kepala Sekolah',
                'role'              => 'headmaster',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}
