<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassroomSubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasSayaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ensure the logged in user is a teacher and has a teacher profile
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Profil guru tidak ditemukan.');
        }

        // Fetch currently active academic year
        $activeYear = AcademicYear::where('is_active', true)->first();

        $groupedAssignments = collect();

        if ($activeYear) {
            // Get teacher's class assignments for the active academic year
            $assignments = ClassroomSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['classroom.students', 'subject'])
                ->get();

            // Group assignments by subject name
            $groupedAssignments = $assignments->groupBy(function ($assignment) {
                return $assignment->subject->name;
            });
        }

        return view('kelas_saya.index', compact('groupedAssignments', 'activeYear'));
    }
}
