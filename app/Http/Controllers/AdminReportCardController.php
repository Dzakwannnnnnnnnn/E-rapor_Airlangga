<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ReportCard;
use App\Models\Student;
use Illuminate\Http\Request;

class AdminReportCardController extends Controller
{
    /**
     * Display listing of classrooms and their report card submission/validation stats.
     */
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $classrooms = Classroom::withCount('students')
            ->with(['homeroomTeacher.user'])
            ->get();

        // Calculate stats for each classroom
        $classroomsStats = $classrooms->map(function ($classroom) use ($activeYear) {
            $studentIds = $classroom->students->pluck('id');
            $totalStudents = $studentIds->count();

            $submittedCount = ReportCard::where('academic_year_id', $activeYear->id)
                ->whereIn('student_id', $studentIds)
                ->where('is_submitted', true)
                ->count();

            $validatedCount = ReportCard::where('academic_year_id', $activeYear->id)
                ->whereIn('student_id', $studentIds)
                ->where('is_validated', true)
                ->count();

            return [
                'classroom' => $classroom,
                'total' => $totalStudents,
                'submitted' => $submittedCount,
                'validated' => $validatedCount,
            ];
        });

        return view('management.report_cards.index', compact('classroomsStats', 'activeYear'));
    }

    /**
     * Display students and their report card status within a classroom.
     */
    public function showClassroom(Classroom $classroom)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $classroom->load(['homeroomTeacher.user', 'students']);
        $students = $classroom->students()->orderBy('name')->get();

        $reportCards = ReportCard::where('academic_year_id', $activeYear->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('management.report_cards.classroom', compact('classroom', 'students', 'reportCards', 'activeYear'));
    }

    /**
     * Validate/sign off a report card.
     */
    public function validateReport(ReportCard $reportCard)
    {
        $reportCard->is_validated = true;
        $reportCard->status = 'validated';
        $reportCard->save();

        return redirect()->back()->with('success', "Rapor untuk {$reportCard->student->name} berhasil disahkan.");
    }

    /**
     * Reject/decline a report card and return it to homeroom teacher as draft.
     */
    public function rejectReport(ReportCard $reportCard)
    {
        $reportCard->is_submitted = false;
        $reportCard->is_validated = false;
        $reportCard->status = 'draft';
        $reportCard->save();

        return redirect()->back()->with('success', "Pengajuan rapor untuk {$reportCard->student->name} ditolak dan dikembalikan ke status Draft.");
    }

    /**
     * Validate/sign off all submitted report cards in a classroom.
     */
    public function validateAll(Classroom $classroom)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        $studentIds = $classroom->students->pluck('id');
        $reportCards = ReportCard::where('academic_year_id', $activeYear->id)
            ->whereIn('student_id', $studentIds)
            ->where('is_submitted', true)
            ->where('is_validated', false)
            ->get();

        if ($reportCards->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pengajuan rapor yang butuh pengesahan di kelas ini.');
        }

        foreach ($reportCards as $rc) {
            $rc->is_validated = true;
            $rc->status = 'validated';
            $rc->save();
        }

        return redirect()->back()->with('success', "Berhasil mengesahkan " . $reportCards->count() . " rapor di kelas {$classroom->name}.");
    }

    /**
     * Naikan kelas otomatis berdasarkan rapor semester genap yang sudah divalidasi.
     *
     * Logic:
     * - Hanya berlaku pada semester "genap" (akhir tahun pelajaran).
     * - Siswa dengan rapor genap yang sudah is_validated = true dipindah ke kelas berikutnya.
     * - Pemetaan kelas: "X PPLG" -> "XI PPLG", "X DKV" -> "XI DKV", dll.
     * - Jika kelas berikutnya tidak ada di sistem -> siswa dilewati (skipped).
     * - Jika siswa sudah di kelas tertinggi -> dianggap lulus.
     */
    public function promoteClasses(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            return redirect()->back()->with('error', 'Tahun ajaran aktif tidak ditemukan.');
        }

        if (strtolower($activeYear->semester) !== 'genap') {
            return redirect()->back()->with('error', 'Kenaikan kelas hanya dapat dilakukan pada semester Genap.');
        }

        // Ambil semua rapor genap yang sudah divalidasi
        $validatedReports = ReportCard::where('academic_year_id', $activeYear->id)
            ->where('is_validated', true)
            ->with(['student.classroom'])
            ->get();

        if ($validatedReports->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada rapor yang disahkan untuk semester genap ini.');
        }

        // Ambil semua kelas yang tersedia
        $allClassrooms = Classroom::all()->keyBy('name');

        // Pemetaan tingkat: prefix kelas saat ini => prefix kelas berikutnya
        $levelMap = [
            'X '   => 'XI ',
            'XI '  => 'XII ',
            'XII ' => null,  // Sudah tingkat tertinggi -> lulus
        ];

        $promoted  = 0;
        $graduated = 0;
        $skipped   = 0;

        foreach ($validatedReports as $rc) {
            $student   = $rc->student;
            $classroom = optional($student)->classroom;

            if (!$student || !$classroom) {
                $skipped++;
                continue;
            }

            $currentName = $classroom->name;
            $nextName    = null;
            $isGraduated = false;

            // Tentukan nama kelas berikutnya berdasarkan prefix tingkat
            foreach ($levelMap as $prefix => $nextPrefix) {
                if (str_starts_with($currentName, $prefix)) {
                    if ($nextPrefix === null) {
                        $isGraduated = true;
                        $graduated++;
                    } else {
                        $suffix   = substr($currentName, strlen($prefix));
                        $nextName = $nextPrefix . $suffix;
                    }
                    break;
                }
            }

            if ($isGraduated) {
                continue;
            }

            if ($nextName === null) {
                $skipped++;
                continue;
            }

            $nextClassroom = $allClassrooms->get($nextName);

            if (!$nextClassroom) {
                $skipped++;
                continue;
            }

            $student->classroom_id = $nextClassroom->id;
            $student->save();
            $promoted++;
        }

        $msg = "Kenaikan kelas selesai: {$promoted} siswa naik kelas";
        if ($graduated > 0) {
            $msg .= ", {$graduated} siswa lulus (kelas tertinggi)";
        }
        if ($skipped > 0) {
            $msg .= ", {$skipped} siswa dilewati (kelas tujuan tidak ditemukan di sistem)";
        }
        $msg .= '.';

        return redirect()->back()->with('success', $msg);
    }
}
