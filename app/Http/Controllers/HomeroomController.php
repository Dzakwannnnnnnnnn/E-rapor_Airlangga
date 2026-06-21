<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomSubjectTeacher;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\ReportCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeroomController extends Controller
{
    /**
     * Get the homeroom classrooms of the logged in teacher.
     */
    private function getHomeroomClassroom()
    {
        $teacher = Auth::user()->teacher;
        if (!$teacher) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();
        if (!$activeYear) {
            abort(404, 'Tahun ajaran aktif tidak ditentukan.');
        }

        // Get classroom managed by this teacher
        $classroom = Classroom::where('homeroom_teacher_id', $teacher->id)->first();

        return [$teacher, $activeYear, $classroom];
    }

    /**
     * Display listing of students in the homeroom classroom.
     */
    public function index()
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();

        if (!$classroom) {
            return view('teacher.homeroom.no_class', compact('activeYear'));
        }

        $students = $classroom->students()->orderBy('name')->get();

        // Get report cards for these students
        $reportCards = ReportCard::where('academic_year_id', $activeYear->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('teacher.homeroom.index', compact('classroom', 'students', 'reportCards', 'activeYear'));
    }

    /**
     * Monitor (pantau) grades for a specific student.
     */
    public function pantau(Student $student)
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();
        abort_if(!$classroom || $student->classroom_id !== $classroom->id, 403);

        // Get all subject assignments for this class
        $assignments = ClassroomSubjectTeacher::where('classroom_id', $classroom->id)
            ->where('academic_year_id', $activeYear->id)
            ->with(['subject', 'teacher.user'])
            ->get();

        // Get saved grades
        $grades = Grade::where('student_id', $student->id)
            ->whereIn('classroom_subject_teacher_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('classroom_subject_teacher_id');

        return view('teacher.homeroom.pantau', compact('student', 'classroom', 'assignments', 'grades', 'activeYear'));
    }

    /**
     * Input page for attendance and homeroom notes.
     */
    public function input(Student $student)
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();
        abort_if(!$classroom || $student->classroom_id !== $classroom->id, 403);

        $attendance = Attendance::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->first();

        $reportCard = ReportCard::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->first();

        return view('teacher.homeroom.input', compact('student', 'classroom', 'attendance', 'reportCard', 'activeYear'));
    }

    /**
     * Save attendance and homeroom notes.
     */
    public function store(Student $student, Request $request)
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();
        abort_if(!$classroom || $student->classroom_id !== $classroom->id, 403);

        $request->validate([
            'hadir' => 'required|integer|min:0',
            'sakit' => 'required|integer|min:0',
            'izin' => 'required|integer|min:0',
            'alpha' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        // 1. Save Attendance
        Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $activeYear->id,
            ],
            [
                'hadir' => $request->hadir,
                'sakit' => $request->sakit,
                'izin' => $request->izin,
                'alpha' => $request->alpha,
            ]
        );

        // 2. Save Report Card Homeroom Note (Draft)
        ReportCard::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $activeYear->id,
            ],
            [
                'description' => $request->description,
            ]
        );

        return redirect()
            ->route('teacher.homeroom.index')
            ->with('success', "Catatan & Kehadiran untuk siswa {$student->name} berhasil disimpan.");
    }

    /**
     * Generate report card for student.
     */
    public function generate(Student $student)
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();
        abort_if(!$classroom || $student->classroom_id !== $classroom->id, 403);

        // Get all subject assignments for this class
        $assignments = ClassroomSubjectTeacher::where('classroom_id', $classroom->id)
            ->where('academic_year_id', $activeYear->id)
            ->get();

        // Get student's grades
        $grades = Grade::where('student_id', $student->id)
            ->whereIn('classroom_subject_teacher_id', $assignments->pluck('id'))
            ->whereNotNull('final_score')
            ->get();

        if ($grades->isEmpty()) {
            return redirect()->back()->with('error', 'Gagal generate. Belum ada nilai mapel yang di-submit oleh guru.');
        }

        // Calculate Average
        $average = round($grades->avg('final_score'), 2);

        // Calculate rank across classroom
        $allStudents = $classroom->students;
        $studentAverages = [];

        foreach ($allStudents as $s) {
            $sGrades = Grade::where('student_id', $s->id)
                ->whereIn('classroom_subject_teacher_id', $assignments->pluck('id'))
                ->whereNotNull('final_score')
                ->get();
            
            $studentAverages[$s->id] = $sGrades->count() > 0 ? $sGrades->avg('final_score') : 0;
        }

        arsort($studentAverages);

        $rank = 1;
        foreach ($studentAverages as $sId => $avg) {
            if ($sId == $student->id) {
                break;
            }
            $rank++;
        }

        // Update/create report card
        ReportCard::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $activeYear->id,
            ],
            [
                'final_score' => $average,
                'rank' => $rank,
                'status' => 'draft',
                'is_validated' => false,
                'is_submitted' => false,
            ]
        );

        return redirect()
            ->route('teacher.homeroom.index')
            ->with('success', "Rapor untuk {$student->name} berhasil digenerate dengan rata-rata nilai {$average} (Peringkat #{$rank}).");
    }

    /**
     * Submit report card to admin.
     */
    public function submit(Student $student)
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();
        abort_if(!$classroom || $student->classroom_id !== $classroom->id, 403);

        $reportCard = ReportCard::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->first();

        if (!$reportCard || $reportCard->final_score === null) {
            return redirect()->back()->with('error', 'Rapor harus digenerate terlebih dahulu sebelum diajukan.');
        }

        $reportCard->is_submitted = true;
        $reportCard->status = 'pending';
        $reportCard->save();

        return redirect()
            ->route('teacher.homeroom.index')
            ->with('success', "Rapor untuk {$student->name} berhasil diajukan untuk disahkan oleh Admin.");
    }

    /**
     * Cancel submission to Admin.
     */
    public function cancel(Student $student)
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();
        abort_if(!$classroom || $student->classroom_id !== $classroom->id, 403);

        $reportCard = ReportCard::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->first();

        if (!$reportCard || !$reportCard->is_submitted || $reportCard->is_validated) {
            return redirect()->back()->with('error', 'Rapor tidak dapat dibatalkan atau sudah disahkan.');
        }

        $reportCard->is_submitted = false;
        $reportCard->status = 'draft';
        $reportCard->save();

        return redirect()
            ->route('teacher.homeroom.index')
            ->with('success', "Pengajuan rapor untuk {$student->name} berhasil dibatalkan.");
    }

    /**
     * Generate report cards for all students in the homeroom classroom.
     */
    public function generateAll()
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();
        if (!$classroom) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $students = $classroom->students;
        $assignments = ClassroomSubjectTeacher::where('classroom_id', $classroom->id)
            ->where('academic_year_id', $activeYear->id)
            ->get();

        if ($assignments->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada mata pelajaran di kelas ini.');
        }

        // Check if there are any grades at all
        $totalGrades = Grade::whereIn('classroom_subject_teacher_id', $assignments->pluck('id'))->count();
        if ($totalGrades === 0) {
            return redirect()->back()->with('error', 'Gagal generate. Belum ada nilai mapel yang di-submit oleh guru.');
        }

        // Compute averages for all students
        $studentAverages = [];
        foreach ($students as $s) {
            $sGrades = Grade::where('student_id', $s->id)
                ->whereIn('classroom_subject_teacher_id', $assignments->pluck('id'))
                ->whereNotNull('final_score')
                ->get();
            
            $studentAverages[$s->id] = $sGrades->count() > 0 ? $sGrades->avg('final_score') : 0;
        }

        // Sort to determine ranks
        arsort($studentAverages);

        // Update/create report card for each student
        $rank = 1;
        $count = 0;
        foreach ($studentAverages as $sId => $avg) {
            $student = $students->firstWhere('id', $sId);
            if (!$student) continue;

            $rc = ReportCard::where('student_id', $sId)
                ->where('academic_year_id', $activeYear->id)
                ->first();

            if (!$rc || !$rc->is_validated) {
                ReportCard::updateOrCreate(
                    [
                        'student_id' => $sId,
                        'academic_year_id' => $activeYear->id,
                    ],
                    [
                        'final_score' => round($avg, 2),
                        'rank' => $rank,
                        'status' => 'draft',
                        'is_validated' => false,
                        'is_submitted' => false,
                    ]
                );
                $count++;
            }
            $rank++;
        }

        return redirect()
            ->route('teacher.homeroom.index')
            ->with('success', "Berhasil memproses rapor untuk {$count} siswa.");
    }

    /**
     * Submit all generated report cards in the homeroom classroom to Admin.
     */
    public function submitAll()
    {
        [$teacher, $activeYear, $classroom] = $this->getHomeroomClassroom();
        if (!$classroom) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $students = $classroom->students;
        
        $reportCards = ReportCard::where('academic_year_id', $activeYear->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->whereNotNull('final_score')
            ->where('is_submitted', false)
            ->where('is_validated', false)
            ->get();

        if ($reportCards->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada rapor draft yang siap diajukan.');
        }

        foreach ($reportCards as $rc) {
            $rc->is_submitted = true;
            $rc->status = 'pending';
            $rc->save();
        }

        return redirect()
            ->route('teacher.homeroom.index')
            ->with('success', "Berhasil mengajukan " . $reportCards->count() . " rapor siswa ke Admin.");
    }
}
