<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassroomSubjectTeacher;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        if (!$teacher) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        // Initialize default values if no active academic year
        $classroomCount = 0;
        $subjectCount = 0;
        $studentCount = 0;
        $assignments = collect();
        $classroomsList = collect();

        if ($activeYear) {
            // Get teacher's subject & classroom assignments for the active academic year
            $assignments = ClassroomSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['classroom.students', 'subject', 'assessments'])
                ->get();

            // Count unique classrooms taught
            $classroomCount = $assignments->pluck('classroom_id')->unique()->count();

            // Count unique subjects taught
            $subjectCount = $assignments->pluck('subject_id')->unique()->count();

            // Fetch distinct classroom models with student counts
            $classroomIds = $assignments->pluck('classroom_id')->unique();
            $classroomsList = Classroom::whereIn('id', $classroomIds)
                ->withCount('students')
                ->get();

            // Count total students taught
            $studentCount = $classroomsList->sum('students_count');
        }

        return view('teacher.dashboard', compact(
            'teacher',
            'activeYear',
            'assignments',
            'classroomCount',
            'subjectCount',
            'studentCount',
            'classroomsList'
        ));
    }
}
