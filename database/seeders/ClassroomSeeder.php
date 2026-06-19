<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Kelas X
        Classroom::create(['name' => 'X PPLG',  'major' => 'Pengembangan Perangkat Lunak dan Gim']);
        Classroom::create(['name' => 'X DKV',   'major' => 'Desain Komunikasi Visual']);

        // Kelas XI
        Classroom::create(['name' => 'XI PPLG', 'major' => 'Pengembangan Perangkat Lunak dan Gim']);
        Classroom::create(['name' => 'XI DKV',  'major' => 'Desain Komunikasi Visual']);
    }
}
