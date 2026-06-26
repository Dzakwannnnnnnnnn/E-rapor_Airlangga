<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    /**
     * Ambil data student yang terhubung ke orang tua yang sedang login.
     */
    private function getStudent()
    {
        $parent = Auth::user()->parent;
        return $parent
            ? Student::with(['classroom', 'attendances', 'reportCards.academicYear'])
                ->where('parent_id', $parent->id)
                ->first()
            : null;
    }

    /**
     * Halaman Ringkasan Home – dalam 5 detik orang tua tahu kondisi anaknya.
     */
    public function index()
    {
        $student = $this->getStudent();

        if (!$student) {
            return view('parent.dashboard', compact('student'));
        }

        // --- Rata-rata Nilai ---
        $grades       = $student->grades()->with('classroomSubjectTeacher.subject')->get();
        $avgScore     = $grades->isNotEmpty() ? round($grades->avg('final_score'), 2) : null;

        // --- Kehadiran ---
        $attendance   = $student->attendances()->first();
        $totalHadir   = $attendance ? ($attendance->hadir + $attendance->sakit + $attendance->izin + $attendance->alpha) : 0;
        $attendancePct = ($totalHadir > 0 && $attendance)
            ? round(($attendance->hadir / $totalHadir) * 100, 1)
            : null;

        // --- Tugas Selesai ---
        $totalAssignments  = 0;
        $doneAssignments   = 0;
        if ($student->classroom) {
            // Ambil semua assignment yang ada di kelas anak
            $cstIds = $student->classroom->classroomSubjectTeachers()
                ->pluck('id');

            $totalAssignments = Assignment::whereIn('classroom_subject_teacher_id', $cstIds)->count();
            $doneAssignments  = $student->assignmentSubmissions()
                ->where('status', 'submitted')
                ->count();
        }
        $taskPct = $totalAssignments > 0
            ? round(($doneAssignments / $totalAssignments) * 100)
            : null;

        // --- Rapor & Peringkat ---
        $reportCard     = $student->reportCards()->latest()->first();
        $totalStudents  = Student::where('classroom_id', $student->classroom_id)->count();

        return view('parent.dashboard', compact(
            'student',
            'grades',
            'avgScore',
            'attendance',
            'attendancePct',
            'totalHadir',
            'totalAssignments',
            'doneAssignments',
            'taskPct',
            'reportCard',
            'totalStudents'
        ));
    }

    /**
     * Halaman Detail Akademik – daftar nilai per mata pelajaran.
     */
    public function academic()
    {
        $student = $this->getStudent();

        if (!$student) {
            return view('parent.academic', ['student' => null, 'grades' => collect()]);
        }

        $grades = $student->grades()
            ->with('classroomSubjectTeacher.subject', 'classroomSubjectTeacher.teacher.user')
            ->get();

        $avgScore = $grades->isNotEmpty() ? round($grades->avg('final_score'), 2) : null;

        return view('parent.academic', compact('student', 'grades', 'avgScore'));
    }

    /**
     * Halaman Detail Kehadiran – statistik absensi semester.
     */
    public function attendance()
    {
        $student    = $this->getStudent();
        $attendance = $student ? $student->attendances()->first() : null;

        $totalHadir = 0;
        $attendancePct = null;

        if ($attendance) {
            $totalHadir = $attendance->hadir + $attendance->sakit + $attendance->izin + $attendance->alpha;
            $attendancePct = $totalHadir > 0
                ? round(($attendance->hadir / $totalHadir) * 100, 1)
                : null;
        }

        return view('parent.attendance', compact('student', 'attendance', 'totalHadir', 'attendancePct'));
    }

    /**
     * Halaman Rapor Resmi – status validasi dan download PDF.
     */
    public function report()
    {
        $student      = $this->getStudent();
        $reportCard   = $student ? $student->reportCards()->with('academicYear')->latest()->first() : null;
        $totalStudents = $student ? Student::where('classroom_id', $student->classroom_id)->count() : 0;

        return view('parent.report', compact('student', 'reportCard', 'totalStudents'));
    }
}
