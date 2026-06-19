<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ─── Mata Pelajaran Umum (shared di semua kelas) ───
        $shared = [
            'PPKN',
            'PJOK',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Sejarah',
            'Matematika',
            'PKK (Proyek Kreatif dan Kewirausahaan)',
        ];

        foreach ($shared as $name) {
            Subject::create(['name' => $name, 'type' => 'academic']);
        }

        // ─── Mata Pelajaran Kejuruan PPLG ───
        Subject::create(['name' => 'Dasar-Dasar PPLG',        'type' => 'academic']); // Kejuruan PPLG
        Subject::create(['name' => 'Database Foundation',     'type' => 'academic']); // Mapil PPLG

        // ─── Mata Pelajaran Kejuruan DKV ───
        Subject::create(['name' => 'Konsentrasi DKV',          'type' => 'academic']); // Kejuruan DKV
        Subject::create(['name' => 'Editing dan Visual Effect', 'type' => 'academic']); // Mapil DKV

        // ─── Ekstrakurikuler ───
        Subject::create(['name' => 'Web Design', 'type' => 'extracurricular']); // Ekskul PPLG
        Subject::create(['name' => 'Editing',    'type' => 'extracurricular']); // Ekskul DKV
    }
}
