<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ReportCard;
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
        $reportCard->publish_at = now();
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
            $rc->publish_at = now();
            $rc->save();
        }

        return redirect()->back()->with('success', "Berhasil mengesahkan " . $reportCards->count() . " rapor di kelas {$classroom->name}.");
    }
}
