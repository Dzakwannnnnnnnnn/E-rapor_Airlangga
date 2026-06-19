<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomSubjectTeacher;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSubjectTeacherSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $ay = AcademicYear::where('is_active', true)->first() ?? AcademicYear::first();
        if (!$ay) return;

        // ─── Helper: resolve Teacher by user name ───
        $teacher = fn($name) => Teacher::whereHas('user', fn($q) => $q->where('name', $name))->first();

        // ─── Helper: resolve Subject by name ───
        $subject = fn($name) => Subject::where('name', $name)->first();

        // ─── Helper: resolve Classroom by name ───
        $class = fn($name) => Classroom::where('name', $name)->first();

        // ─── Helper: create assignment ───
        $assign = function ($classroomName, $subjectName, $teacherName) use ($ay, $teacher, $subject, $class) {
            $c = $class($classroomName);
            $s = $subject($subjectName);
            $t = $teacher($teacherName);
            if ($c && $s && $t) {
                ClassroomSubjectTeacher::firstOrCreate([
                    'classroom_id'     => $c->id,
                    'subject_id'       => $s->id,
                    'teacher_id'       => $t->id,
                    'academic_year_id' => $ay->id,
                ]);
            }
        };

        // ═══════════════════════════════════════════════════
        // MAPEL UMUM — 1 guru mengajar di semua 4 kelas
        // ═══════════════════════════════════════════════════
        $sharedSubjects = [
            'PPKN'                               => 'Bpk. Sudarsono, S.Pd.',
            'PJOK'                               => 'Ibu Rahmawati, S.Pd.',
            'Bahasa Indonesia'                   => 'Bpk. Firmansyah, S.Pd.',
            'Bahasa Inggris'                     => 'Ibu Nurlaila, S.Pd.',
            'Sejarah'                            => 'Bpk. Hendra Susilo, M.Pd.',
            'Matematika'                         => 'Ibu Siti Maryam, S.Pd.',
            'PKK (Proyek Kreatif dan Kewirausahaan)' => 'Bpk. Wahyudi, S.E., M.Pd.',
        ];

        $allClasses = ['X PPLG', 'X DKV', 'XI PPLG', 'XI DKV'];

        foreach ($sharedSubjects as $subjectName => $teacherName) {
            foreach ($allClasses as $className) {
                $assign($className, $subjectName, $teacherName);
            }
        }

        // ═══════════════════════════════════════════════════
        // MAPEL KEJURUAN PPLG — X PPLG & XI PPLG
        // ═══════════════════════════════════════════════════
        foreach (['X PPLG', 'XI PPLG'] as $className) {
            $assign($className, 'Dasar-Dasar PPLG',    'Bpk. Rizki Pratama, S.Kom.');
            $assign($className, 'Database Foundation',  'Bpk. Andi Setiawan, S.Kom.');
        }

        // ═══════════════════════════════════════════════════
        // MAPEL KEJURUAN DKV — X DKV & XI DKV
        // ═══════════════════════════════════════════════════
        foreach (['X DKV', 'XI DKV'] as $className) {
            $assign($className, 'Konsentrasi DKV',          'Ibu Dewi Kartika, S.Ds.');
            $assign($className, 'Editing dan Visual Effect', 'Ibu Larasati, S.Sn.');
        }

        // ═══════════════════════════════════════════════════
        // EKSTRAKURIKULER
        // ═══════════════════════════════════════════════════
        foreach (['X PPLG', 'XI PPLG'] as $className) {
            $assign($className, 'Web Design', 'Bpk. Fadlan Maulana, S.Kom.');
        }

        foreach (['X DKV', 'XI DKV'] as $className) {
            $assign($className, 'Editing', 'Bpk. Dimas Arifin, S.Sn.');
        }
    }
}
