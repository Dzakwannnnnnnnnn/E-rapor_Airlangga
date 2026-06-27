<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class HeadmasterController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // 1. HOME — Ringkasan Global
    // ─────────────────────────────────────────────────────────────
    public function index()
    {
        $activeYear   = AcademicYear::where('is_active', true)->first();
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses  = Classroom::count();

        $pendingReports   = 0;
        $validatedReports = 0;
        $submittedReports = 0;
        $classroomsStats  = collect();

        if ($activeYear) {
            $classrooms = Classroom::withCount('students')->with(['homeroomTeacher.user'])->get();

            $classroomsStats = $classrooms->map(function ($classroom) use ($activeYear) {
                $studentIds    = $classroom->students->pluck('id');
                $totalStudents = $studentIds->count();

                $submitted  = ReportCard::where('academic_year_id', $activeYear->id)
                    ->whereIn('student_id', $studentIds)->where('is_submitted', true)->count();
                $validated  = ReportCard::where('academic_year_id', $activeYear->id)
                    ->whereIn('student_id', $studentIds)->where('is_validated', true)->count();
                $pending    = ReportCard::where('academic_year_id', $activeYear->id)
                    ->whereIn('student_id', $studentIds)
                    ->where('is_submitted', true)->where('is_validated', false)->count();

                return [
                    'classroom' => $classroom,
                    'total'     => $totalStudents,
                    'submitted' => $submitted,
                    'validated' => $validated,
                    'pending'   => $pending,
                ];
            });

            $pendingReports   = $classroomsStats->sum('pending');
            $validatedReports = $classroomsStats->sum('validated');
            $submittedReports = $classroomsStats->sum('submitted');
        }

        return view('headmaster.dashboard', compact(
            'activeYear',
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'pendingReports',
            'validatedReports',
            'submittedReports',
            'classroomsStats'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // 2. AKADEMIK — Laporan Nilai Sekolah (Bahasa Lebih Sederhana)
    // ─────────────────────────────────────────────────────────────
    public function akademik(Request $request)
    {
        $academicYears = AcademicYear::orderByDesc('year')->orderByDesc('semester')->get();
        $selectedYear  = $request->academic_year_id
            ? AcademicYear::find($request->academic_year_id)
            : AcademicYear::where('is_active', true)->first();

        // Ambil semua nilai murid di tahun ajaran yang dipilih
        $allGrades = collect();
        if ($selectedYear) {
            $allGrades = Grade::whereHas('classroomSubjectTeacher', function($q) use ($selectedYear) {
                $q->where('academic_year_id', $selectedYear->id);
            })
            ->with(['classroomSubjectTeacher.subject'])
            ->where('final_score', '>', 0)
            ->get();
        }

        // KKM Sekolah diatur 75
        $kkmGlobal = 75;

        // 1. STATISTIK SEKOLAH
        $schoolAvg         = $allGrades->isNotEmpty() ? round($allGrades->avg('final_score'), 1) : 0;
        $totalGradesCount  = $allGrades->count();
        $aboveKkmCount     = $allGrades->where('final_score', '>=', $kkmGlobal)->count();
        $schoolAboveKkmPct = $totalGradesCount > 0 ? round(($aboveKkmCount / $totalGradesCount) * 100, 1) : 0;

        // 2. URUTAN PELAJARAN DARI YANG TERSULIT (Rata-rata Terendah)
        $subjectStats = $allGrades->filter(fn($g) => $g->classroomSubjectTeacher?->subject)
            ->groupBy(fn($g) => $g->classroomSubjectTeacher->subject->name)
            ->map(function($group) {
                return [
                    'avg'   => round($group->avg('final_score'), 1),
                    'total' => $group->count()
                ];
            })->sortBy('avg');

        // 3. STATISTIK PER KELAS
        $classrooms = Classroom::with(['students'])->get();

        $classroomStats = $classrooms->map(function ($classroom) use ($allGrades, $schoolAvg, $kkmGlobal) {
            $studentIds  = $classroom->students->pluck('id');
            $classGrades = $allGrades->whereIn('student_id', $studentIds);

            if ($classGrades->isEmpty()) {
                return [
                    'classroom'   => $classroom,
                    'avg'         => null,
                    'gap'         => null,
                    'underKkmPct' => 0,
                    'dist'        => ['A' => 0, 'B+' => 0, 'B' => 0, 'C' => 0],
                    'lowest'      => null,
                    'highest'     => null,
                ];
            }

            $avg = round($classGrades->avg('final_score'), 1);

            // Perbandingan Nilai Kelas vs Nilai Rata-rata Sekolah
            $gap = round($avg - $schoolAvg, 1);

            // Persentase Siswa di Bawah KKM
            $underKkmCount = $classGrades->where('final_score', '<', $kkmGlobal)->count();
            $underKkmPct   = round(($underKkmCount / $classGrades->count()) * 100, 1);

            // Pengelompokan Nilai
            $dist = ['A' => 0, 'B+' => 0, 'B' => 0, 'C' => 0];
            foreach ($classGrades as $g) {
                $s = (float) $g->final_score;
                if ($s >= 88)      $dist['A']++;
                elseif ($s >= 82)  $dist['B+']++;
                elseif ($s >= 75)  $dist['B']++;
                else               $dist['C']++;
            }

            // Hitung rata-rata per mata pelajaran di kelas ini
            $subjectAvg = $classGrades->filter(fn($g) => $g->classroomSubjectTeacher?->subject)
                ->groupBy(fn($g) => $g->classroomSubjectTeacher->subject->name)
                ->map(fn($group) => round($group->avg('final_score'), 1));

            $lowest = null;
            $highest = null;

            if ($subjectAvg->isNotEmpty()) {
                // Cari Pelajaran Nilai Terendah
                $minScore  = $subjectAvg->min();
                $minName   = $subjectAvg->filter(fn($v) => $v == $minScore)->keys()->first();
                $lowest    = ['name' => $minName, 'score' => $minScore];

                // Cari Pelajaran Nilai Tertinggi
                $maxScore  = $subjectAvg->max();
                $maxName   = $subjectAvg->filter(fn($v) => $v == $maxScore)->keys()->first();
                $highest   = ['name' => $maxName, 'score' => $maxScore];
            }

            return [
                'classroom'   => $classroom,
                'avg'         => $avg,
                'gap'         => $gap,
                'underKkmPct' => $underKkmPct,
                'dist'        => $dist,
                'lowest'      => $lowest,
                'highest'     => $highest,
            ];
        })->sortByDesc('avg');

        return view('headmaster.akademik', compact(
            'classroomStats',
            'academicYears',
            'selectedYear',
            'schoolAvg',
            'schoolAboveKkmPct',
            'subjectStats'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    // 3. KEHADIRAN SEKOLAH — Rekap per kelas
    // ─────────────────────────────────────────────────────────────
    public function kehadiran(Request $request)
    {
        $academicYears = AcademicYear::orderByDesc('year')->orderByDesc('semester')->get();
        $selectedYear  = $request->academic_year_id
            ? AcademicYear::find($request->academic_year_id)
            : AcademicYear::where('is_active', true)->first();

        $classrooms = Classroom::with('students')->get();

        $kehadiranStats = $classrooms->map(function ($classroom) use ($selectedYear) {
            $studentIds = $classroom->students->pluck('id');
            if ($studentIds->isEmpty()) {
                return [
                    'classroom'   => $classroom,
                    'hadir'       => 0,
                    'sakit'       => 0,
                    'izin'        => 0,
                    'alpha'       => 0,
                    'total'       => 0,
                    'hadirPct'    => 0,
                    'alphaPct'    => 0,
                ];
            }

            $attendances = Attendance::whereIn('student_id', $studentIds)->get();

            $hadir = $attendances->sum('hadir');
            $sakit = $attendances->sum('sakit');
            $izin  = $attendances->sum('izin');
            $alpha = $attendances->sum('alpha');
            $total = $hadir + $sakit + $izin + $alpha;

            return [
                'classroom'   => $classroom,
                'hadir'       => $hadir,
                'sakit'       => $sakit,
                'izin'        => $izin,
                'alpha'       => $alpha,
                'total'       => $total,
                'hadirPct'    => $total > 0 ? round(($hadir / $total) * 100, 1) : 0,
                'alphaPct'    => $total > 0 ? round(($alpha / $total) * 100, 1) : 0,
            ];
        })->sortBy('hadirPct'); // Kelas dengan kehadiran paling rendah di atas

        return view('headmaster.kehadiran', compact('kehadiranStats', 'academicYears', 'selectedYear'));
    }

    // ─────────────────────────────────────────────────────────────
    // 4. PENGESAHAN RAPOR — Index (Daftar Kelas)
    // ─────────────────────────────────────────────────────────────
    public function pengesahanIndex()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $classrooms = Classroom::withCount('students')->with(['homeroomTeacher.user'])->get();

        $classroomsStats = $classrooms->map(function ($classroom) use ($activeYear) {
            $studentIds    = $classroom->students->pluck('id');
            $totalStudents = $studentIds->count();

            $submittedCount = ReportCard::where('academic_year_id', $activeYear->id)
                ->whereIn('student_id', $studentIds)->where('is_submitted', true)->count();
            $validatedCount = ReportCard::where('academic_year_id', $activeYear->id)
                ->whereIn('student_id', $studentIds)->where('is_validated', true)->count();

            return [
                'classroom' => $classroom,
                'total'     => $totalStudents,
                'submitted' => $submittedCount,
                'validated' => $validatedCount,
            ];
        });

        return view('headmaster.pengesahan.index', compact('classroomsStats', 'activeYear'));
    }

    // ─────────────────────────────────────────────────────────────
    // 4b. PENGESAHAN — Detail per Kelas
    // ─────────────────────────────────────────────────────────────
    public function pengesahanKelas(Classroom $classroom)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $classroom->load(['homeroomTeacher.user', 'students']);
        $students = $classroom->students()->orderBy('name')->get();

        $reportCards = ReportCard::where('academic_year_id', $activeYear->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()->keyBy('student_id');

        return view('headmaster.pengesahan.classroom', compact('classroom', 'students', 'reportCards', 'activeYear'));
    }

    // ─────────────────────────────────────────────────────────────
    // 4c. Sahkan satu rapor
    // ─────────────────────────────────────────────────────────────
    public function validateReport(ReportCard $reportCard)
    {
        $reportCard->is_validated = true;
        $reportCard->status       = 'validated';
        $reportCard->save();

        return redirect()->back()->with('success', "Rapor {$reportCard->student->name} berhasil disahkan.");
    }

    // ─────────────────────────────────────────────────────────────
    // 4d. Tolak rapor
    // ─────────────────────────────────────────────────────────────
    public function rejectReport(ReportCard $reportCard)
    {
        $reportCard->is_submitted = false;
        $reportCard->is_validated = false;
        $reportCard->status       = 'draft';
        $reportCard->save();

        return redirect()->back()->with('success', "Rapor {$reportCard->student->name} ditolak dan dikembalikan ke wali kelas.");
    }

    // ─────────────────────────────────────────────────────────────
    // 4e. Sahkan SEMUA rapor yang sudah disubmit dalam satu kelas
    // ─────────────────────────────────────────────────────────────
    public function validateAll(Classroom $classroom)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        // Pastikan classroom memiliki relasi students ter-load
        $classroom->load('students');
        $studentIds = $classroom->students->pluck('id');

        if ($studentIds->isEmpty()) {
            return redirect()->back()->with('error', 'Kelas ini tidak memiliki siswa.');
        }

        $reportCards = ReportCard::where('academic_year_id', $activeYear->id)
            ->whereIn('student_id', $studentIds)
            ->where('is_submitted', true)
            ->where('is_validated', false)
            ->get();

        if ($reportCards->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada rapor yang menunggu pengesahan di kelas ini.');
        }

        $count = 0;
        foreach ($reportCards as $rc) {
            $rc->is_validated = true;
            $rc->status       = 'validated';
            $rc->save();
            $count++;
        }

        return redirect()->back()->with('success', "Berhasil mengesahkan {$count} rapor di kelas {$classroom->name}.");
    }
}
