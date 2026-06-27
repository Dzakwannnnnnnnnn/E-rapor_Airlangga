<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Parents;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Data Mentah untuk Summary Cards
        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $parentCount = Parents::count();
        $classroomCount = Classroom::count();
        $subjectCount = Subject::count();
        $activeYear = AcademicYear::where('is_active', 1)->first();

        // 2. Chart: Komposisi Pengguna
        $chartUserComposition = [
            'labels' => ['Siswa', 'Guru', 'Wali Murid'],
            'data' => [$studentCount, $teacherCount, $parentCount]
        ];

        // 3. Chart: Distribusi Siswa per Kelas
        // Menggunakan withCount asumsi Model Classroom punya relasi: public function students() { return $this->hasMany(Student::class); }
        $classrooms = Classroom::withCount('students')->get();
        $chartStudentsPerClass = [
            'labels' => $classrooms->pluck('name')->toArray(),
            'data' => $classrooms->pluck('students_count')->toArray()
        ];

        // 4. Chart: Mata Pelajaran per Tipe (Berdasarkan kolom 'type' enum)
        $subjects = Subject::select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        // Map label agar lebih rapi (academic -> Akademik)
        $typeLabels = $subjects->pluck('type')->map(function($type) {
            return $type === 'academic' ? 'Akademik' : 'Ekstrakurikuler';
        })->toArray();

        $chartSubjectsPerType = [
            'labels' => $typeLabels,
            'data' => $subjects->pluck('total')->toArray()
        ];

        // 5. Chart: Guru Berdasarkan Gender (Berdasarkan kolom 'gender' enum)
        $teachers = Teacher::select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();

        // Map label L/P menjadi Laki-laki/Perempuan
        $genderLabels = $teachers->pluck('gender')->map(function($gender) {
            return $gender === 'L' ? 'Laki-Laki' : 'Perempuan';
        })->toArray();

        $chartTeachersPerGender = [
            'labels' => $genderLabels,
            'data' => $teachers->pluck('total')->toArray()
        ];

        return view('admin.dashboard', compact(
            'studentCount',
            'teacherCount',
            'parentCount',
            'classroomCount',
            'subjectCount',
            'activeYear',
            'chartUserComposition',
            'chartStudentsPerClass',
            'chartSubjectsPerType',
            'chartTeachersPerGender'
        ));
    }
}
