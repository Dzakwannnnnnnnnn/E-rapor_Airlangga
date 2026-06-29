<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\ClassroomSubjectTeacher;
use App\Models\Grade;
use App\Models\ReportCard;
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
            ? $parent->students()->with(['classroom', 'attendances', 'reportCards.academicYear'])->first()
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

        // --- Rapor & Peringkat ---
        $reportCard     = $student->reportCards()->latest()->first();
        $totalStudents  = Student::where('classroom_id', $student->classroom_id)->count();

        // --- Mata Pelajaran Terendah (Risk Indicator) ---
        $lowestGrade = $grades
            ->filter(fn($g) => $g->final_score !== null && (float)$g->final_score > 0)
            ->sortBy('final_score')
            ->first();

        return view('parent.dashboard', compact(
            'student',
            'grades',
            'avgScore',
            'attendance',
            'attendancePct',
            'totalHadir',
            'reportCard',
            'totalStudents',
            'lowestGrade'
        ));
    }

    /**
     * Halaman Detail Akademik – daftar nilai per mata pelajaran.
     * Mendukung filter semester via query param ?academic_year_id=
     */
    public function academic(Request $request)
    {
        $student = $this->getStudent();

        // Ambil semua tahun ajaran untuk dropdown
        $academicYears = AcademicYear::orderByDesc('year')->orderByDesc('semester')->get();

        if (!$student) {
            return view('parent.academic', [
                'student'       => null,
                'grades'        => collect(),
                'academicYears' => $academicYears,
                'selectedYear'  => null,
            ]);
        }

        // Tentukan tahun ajaran terpilih
        $selectedYearId = $request->query('academic_year_id');
        $selectedYear   = $selectedYearId
            ? $academicYears->firstWhere('id', $selectedYearId)
            : $academicYears->firstWhere('is_active', true) ?? $academicYears->first();

        // Filter grade berdasarkan tahun ajaran terpilih melalui relasi CST
        $grades = $student->grades()
            ->with([
                'classroomSubjectTeacher.subject',
                'classroomSubjectTeacher.teacher.user',
                'classroomSubjectTeacher.academicYear',
            ])
            ->whereHas('classroomSubjectTeacher', function ($q) use ($selectedYear) {
                if ($selectedYear) {
                    $q->where('academic_year_id', $selectedYear->id);
                }
            })
            ->get();

        $avgScore = $grades->isNotEmpty() ? round($grades->avg('final_score'), 2) : null;

        return view('parent.academic', compact('student', 'grades', 'avgScore', 'academicYears', 'selectedYear'));
    }

    /**
     * Halaman Detail Mata Pelajaran – komponen penilaian, tugas, catatan guru.
     */
    public function academicSubject(Request $request, $cstId)
    {
        $student = $this->getStudent();

        if (!$student) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Pastikan CST ini memang milik kelas siswa
        $cst = ClassroomSubjectTeacher::with([
            'subject',
            'teacher.user',
            'academicYear',
        ])->findOrFail($cstId);

        // Pastikan siswa memang di kelas ini
        if ($student->classroom_id !== $cst->classroom_id) {
            abort(403, 'Data tidak sesuai dengan siswa.');
        }

        // Nilai akhir & catatan guru
        $grade = $student->grades()
            ->where('classroom_subject_teacher_id', $cstId)
            ->first();

        // Komponen penilaian (Ulangan Harian, Tugas, PAS) beserta nilai siswa
        $assessments = $cst->assessments()
            ->with(['gradeEntries' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->orderBy('type')
            ->orderBy('sequence')
            ->orderBy('date')
            ->get();

        return view('parent.academic_subject', compact(
            'student',
            'cst',
            'grade',
            'assessments'
        ));
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
     * Halaman Rapor – daftar semester + riwayat.
     */
    public function report()
    {
        $student      = $this->getStudent();
        $allReports   = collect();
        $activeYear   = AcademicYear::where('is_active', true)->first();
        $totalStudents = 0;

        if ($student) {
            $allReports = $student->reportCards()
                ->with('academicYear')
                ->orderByDesc('created_at')
                ->get();
            $totalStudents = Student::where('classroom_id', $student->classroom_id)->count();
        }

        // Rapor aktif = laporan tahun ajaran aktif
        $activeReport = $allReports->firstWhere('academic_year_id', optional($activeYear)->id);

        // Riwayat = semua rapor kecuali yang aktif saat ini
        $historyReports = $allReports->filter(
            fn($r) => $r->academic_year_id !== optional($activeYear)->id
        );

        return view('parent.report', compact(
            'student',
            'activeYear',
            'activeReport',
            'historyReports',
            'totalStudents'
        ));
    }

    /**
     * Halaman Detail Rapor – tampilan rapor fisik lengkap.
     */
    public function reportView(ReportCard $reportCard)
    {
        $student = $this->getStudent();

        if (!$student || $reportCard->student_id !== $student->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $reportCard->load('academicYear');

        // Nilai per mapel untuk semester ini
        $grades = Grade::where('student_id', $student->id)
            ->whereHas('classroomSubjectTeacher', function ($q) use ($reportCard) {
                $q->where('academic_year_id', $reportCard->academic_year_id);
            })
            ->with([
                'classroomSubjectTeacher.subject',
                'classroomSubjectTeacher.teacher.user',
            ])
            ->get();

        // Absensi semester ini
        $attendance = Attendance::where('student_id', $student->id)
            ->where('academic_year_id', $reportCard->academic_year_id)
            ->first();

        $totalStudents = Student::where('classroom_id', $student->classroom_id)->count();

        return view('parent.report_view', compact(
            'student',
            'reportCard',
            'grades',
            'attendance',
            'totalStudents'
        ));
    }
}
