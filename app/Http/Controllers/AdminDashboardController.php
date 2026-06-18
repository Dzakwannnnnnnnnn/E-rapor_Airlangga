<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Parents;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();

        $teacherCount = Teacher::count();

        $parentCount = Parents::count();

        $classroomCount = Classroom::count();

        $subjectCount = Subject::count();

        $activeYear = AcademicYear::where('is_active', true)
            ->first();

        return view('admin.dashboard', compact(
            'studentCount',
            'teacherCount',
            'parentCount',
            'classroomCount',
            'subjectCount',
            'activeYear'
        ));
    }
}
