<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassroomSubjectTeacher;
use App\Models\Subject;
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

    /**
     * Display subjects list to configure KKM.
     */
    public function kkm()
    {
        $user = Auth::user();
        $teacher = $user->teacher;
        
        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Profil guru tidak ditemukan.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        $subjects = collect();

        if ($activeYear) {
            $assignments = ClassroomSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['subject'])
                ->get();

            $subjects = $assignments->map(fn($a) => $a->subject)->unique('id');
        }

        return view('kelas_saya.kkm', compact('subjects', 'activeYear'));
    }

    /**
     * Update KKM of a subject.
     */
    public function setKkm(Subject $subject, Request $request)
    {
        // Ensure the teacher is authorized to edit this subject's KKM
        $teacher = Auth::user()->teacher;
        $activeYear = AcademicYear::where('is_active', true)->first();

        $hasAssignment = ClassroomSubjectTeacher::where('teacher_id', $teacher->id)
            ->where('subject_id', $subject->id)
            ->where('academic_year_id', $activeYear->id ?? 0)
            ->exists();

        abort_if(!$hasAssignment, 403, 'Anda tidak mengampu mata pelajaran ini.');

        $request->validate([
            'kkm' => 'required|integer|min:0|max:100',
        ]);

        $subject->kkm = $request->kkm;
        $subject->save();

        return redirect()->back()->with('success', 'KKM untuk mata pelajaran ' . $subject->name . ' berhasil diperbarui menjadi ' . $request->kkm);
    }
}
