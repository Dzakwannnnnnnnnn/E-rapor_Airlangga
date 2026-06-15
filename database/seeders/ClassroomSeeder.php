<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classroom::create([
            'name' => 'XI PPLG',
            'major' => 'Pengembangan Perangkat Lunak dan Gim',
        ]);

        Classroom::create([
            'name' => 'XI TJKT',
            'major' => 'Teknik Jaringan Komputer & Telekomunikasi',
        ]);
    }
}

