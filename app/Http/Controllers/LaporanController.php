<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Assessment;
use App\Models\ClassroomSubjectTeacher;
use App\Models\GradeEntry;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Get the teacher's assignments for the active academic year.
     */
    private function getTeacherAndAssignments()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'Profil guru tidak ditemukan.');
        }

        $activeYear = AcademicYear::where('is_active', true)->first();

        $assignments = collect();

        if ($activeYear) {
            $assignments = ClassroomSubjectTeacher::where('teacher_id', $teacher->id)
                ->where('academic_year_id', $activeYear->id)
                ->with(['classroom.students', 'subject', 'assessments'])
                ->get();
        }

        return [$teacher, $activeYear, $assignments];
    }

    /**
     * Main Laporan page: active year + list of classrooms taught.
     */
    public function index()
    {
        [$teacher, $activeYear, $assignments] = $this->getTeacherAndAssignments();

        $assignmentStats = $assignments->map(function ($assignment) {
            $students      = $assignment->classroom->students;
            $assessments   = $assignment->assessments;
            $totalStudents = $students->count();

            if ($totalStudents === 0 || $assessments->isEmpty()) {
                return [
                    'assignment'     => $assignment,
                    'total'          => $totalStudents,
                    'filled'         => 0,
                    'needed'         => 0,
                    'complete'       => false,
                    'status'         => 'belum_diproses', // [Belum Diproses]
                ];
            }

            $assessmentIds = $assessments->pluck('id');
            $studentIds    = $students->pluck('id');

            // Grade entries that exist AND have a score
            $filled = GradeEntry::whereIn('assessment_id', $assessmentIds)
                ->whereIn('student_id', $studentIds)
                ->whereNotNull('score')
                ->count();

            $needed  = $assessments->count() * $totalStudents;
            $complete = ($filled >= $needed);

            // Fetch saved grades count
            $savedGradesCount = Grade::where('classroom_subject_teacher_id', $assignment->id)
                ->whereIn('student_id', $studentIds)
                ->count();

            // Determine status
            if ($assignment->is_submitted) {
                $status = 'sudah_dikirim'; // [Sudah Dikirim ke Wali Kelas]
            } elseif ($savedGradesCount > 0) {
                $status = 'draft_deskripsi'; // [Draft Deskripsi]
            } else {
                $status = 'belum_diproses'; // [Belum Diproses]
            }

            return [
                'assignment'     => $assignment,
                'total'          => $totalStudents,
                'filled'         => $filled,
                'needed'         => $needed,
                'complete'       => $complete,
                'status'         => $status,
            ];
        });

        return view('kelas_saya.laporan.index', compact('teacher', 'activeYear', 'assignments', 'assignmentStats'));
    }

    /**
     * Unified detail view per classroom.
     */
    public function show(ClassroomSubjectTeacher $assignment)
    {
        $this->authorizeAssignment($assignment);

        $assignment->load(['classroom.students', 'subject', 'assessments', 'academicYear']);

        $students    = $assignment->classroom->students->sortBy('name')->values();
        $assessments = $assignment->assessments;
        $kkm         = $assignment->subject->kkm ?? 75;
        $isSubmitted = $assignment->is_submitted;

        $assessmentIds = $assessments->pluck('id');
        $studentIds    = $students->pluck('id');

        $gradeEntriesMap = GradeEntry::whereIn('assessment_id', $assessmentIds)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->keyBy(fn($ge) => "{$ge->assessment_id}_{$ge->student_id}");

        // Fetch existing grades
        $existingGrades = Grade::where('classroom_subject_teacher_id', $assignment->id)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->keyBy('student_id');

        // Compile students data
        $previews = $students->map(function ($student) use ($assessments, $gradeEntriesMap, $existingGrades, $assignment, $kkm) {
            $details = $this->calculateStudentScoreAndDetails($student, $assignment, $gradeEntriesMap);
            $final = $details['final'];
            $hasAll = $details['hasAll'];

            // Look up existing grade record
            $gradeRecord = $existingGrades->get($student->id);

            if ($gradeRecord) {
                $final = $gradeRecord->final_score !== null ? (float) $gradeRecord->final_score : $final;
                $description = $gradeRecord->description;
            } else {
                $description = $final !== null ? $this->generateAutoDescription($details, $assignment) : null;
            }

            $predikat = null;
            if ($final !== null) {
                if ($final >= 90) { $predikat = 'A'; }
                elseif ($final >= 80) { $predikat = 'B'; }
                elseif ($final >= 70) { $predikat = 'C'; }
                else { $predikat = 'D'; }
            }

            return [
                'student'   => $student,
                'final'     => $final,
                'predikat'  => $predikat,
                'description' => $description,
                'hasAll'    => $hasAll,
                'is_saved'  => $gradeRecord !== null,
            ];
        });

        return view('kelas_saya.laporan.detail', compact('assignment', 'previews', 'kkm', 'isSubmitted', 'students'));
    }

    /**
     * Save report grades as draft.
     */
    public function saveDraft(ClassroomSubjectTeacher $assignment, Request $request)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->is_submitted) {
            return redirect()->back()->with('error', 'Nilai sudah dikirim ke Wali Kelas dan dikunci.');
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*.final_score' => 'nullable|numeric|min:0|max:100',
            'grades.*.description' => 'nullable|string|max:1000',
        ]);

        foreach ($request->grades as $studentId => $data) {
            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'classroom_subject_teacher_id' => $assignment->id,
                ],
                [
                    'final_score' => $data['final_score'],
                    'description' => $data['description'],
                ]
            );
        }

        return redirect()->back()->with('success', 'Draft nilai dan deskripsi berhasil disimpan.');
    }

    /**
     * Submit final report grades to Homeroom Teacher (Wali Kelas) and lock them.
     */
    public function submitFinal(ClassroomSubjectTeacher $assignment, Request $request)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->is_submitted) {
            return redirect()->back()->with('error', 'Nilai sudah dikirim ke Wali Kelas.');
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*.final_score' => 'required|numeric|min:0|max:100',
            'grades.*.description' => 'required|string|max:1000',
        ]);

        // Save all data first
        foreach ($request->grades as $studentId => $data) {
            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'classroom_subject_teacher_id' => $assignment->id,
                ],
                [
                    'final_score' => $data['final_score'],
                    'description' => $data['description'],
                ]
            );
        }

        // Lock assignment
        $assignment->is_submitted = true;
        $assignment->save();

        return redirect()->back()->with('success', 'Nilai rapor berhasil dikirim dan dikunci.');
    }

    /**
     * Cancel submission to unlock report card values.
     */
    public function cancelSubmission(ClassroomSubjectTeacher $assignment)
    {
        $this->authorizeAssignment($assignment);

        if (!$assignment->is_submitted) {
            return redirect()->back()->with('error', 'Nilai belum dikirim.');
        }

        $assignment->is_submitted = false;
        $assignment->save();

        return redirect()->back()->with('success', 'Pengiriman nilai berhasil dibatalkan. Kunci pengisian telah dibuka.');
    }

    /**
     * Regenerate description automatically.
     */
    public function regenerate(ClassroomSubjectTeacher $assignment)
    {
        $this->authorizeAssignment($assignment);

        if ($assignment->is_submitted) {
            return redirect()->back()->with('error', 'Nilai sudah dikirim ke Wali Kelas dan dikunci.');
        }

        $assignment->load(['classroom.students', 'subject', 'assessments']);
        $students    = $assignment->classroom->students;
        $assessments = $assignment->assessments;

        $assessmentIds = $assessments->pluck('id');
        $studentIds    = $students->pluck('id');

        $gradeEntriesMap = GradeEntry::whereIn('assessment_id', $assessmentIds)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->keyBy(fn($ge) => "{$ge->assessment_id}_{$ge->student_id}");

        foreach ($students as $student) {
            $details = $this->calculateStudentScoreAndDetails($student, $assignment, $gradeEntriesMap);

            if ($details['final'] !== null) {
                Grade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'classroom_subject_teacher_id' => $assignment->id,
                    ],
                    [
                        'final_score' => $details['final'],
                        'description' => $this->generateAutoDescription($details, $assignment),
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Seluruh deskripsi berhasil diregenerasi otomatis.');
    }

    /**
     * Helper to compute student score components and details.
     */
    private function calculateStudentScoreAndDetails(Student $student, ClassroomSubjectTeacher $assignment, $gradeEntriesMap)
    {
        $assessments = $assignment->assessments;
        $weightedSum = 0;
        $totalWeight = 0;
        $hasAll = true;

        $uhScores = [];
        $tugasScores = [];
        $pasScores = [];

        foreach ($assessments as $assessment) {
            $ge = $gradeEntriesMap["{$assessment->id}_{$student->id}"] ?? null;
            if ($ge && $ge->score !== null) {
                $score = (float) $ge->score;
                $weightedSum += $score * (float) $assessment->weight;
                $totalWeight += (float) $assessment->weight;

                if ($assessment->type === 'uh') {
                    $uhScores[] = $score;
                } elseif ($assessment->type === 'tugas') {
                    $tugasScores[] = $score;
                } elseif ($assessment->type === 'pas') {
                    $pasScores[] = $score;
                }
            } else {
                $hasAll = false;
            }
        }

        $final = ($totalWeight > 0) ? round($weightedSum / 100, 2) : null;

        $uhAvg = count($uhScores) > 0 ? array_sum($uhScores) / count($uhScores) : null;
        $tugasAvg = count($tugasScores) > 0 ? array_sum($tugasScores) / count($tugasScores) : null;
        $pasVal = count($pasScores) > 0 ? array_sum($pasScores) / count($pasScores) : null;

        return [
            'final' => $final,
            'hasAll' => $hasAll,
            'uh_avg' => $uhAvg,
            'tugas_avg' => $tugasAvg,
            'pas_val' => $pasVal,
        ];
    }

    /**
     * Auto generate competence achievement description (Capaian Kompetensi).
     */
    private function generateAutoDescription($details, $assignment)
    {
        $final = $details['final'];
        if ($final === null) return 'Nilai belum lengkap.';

        // Determine level word
        if ($final >= 90) {
            $level = "Menunjukkan penguasaan yang sangat baik";
        } elseif ($final >= 80) {
            $level = "Menunjukkan penguasaan yang baik";
        } elseif ($final >= 70) {
            $level = "Menunjukkan penguasaan yang cukup";
        } else {
            $level = "Perlu peningkatan dan bimbingan";
        }

        // Collect topic descriptions from assessments if available
        $topics = [];
        foreach ($assignment->assessments as $assessment) {
            if ($assessment->description) {
                $topics[] = trim($assessment->description);
            }
        }

        if (empty($topics)) {
            if ($final >= 90) {
                $materi = "memahami seluruh materi pembelajaran, pengerjaan tugas akademik harian, evaluasi semester, serta sangat terampil dalam menyajikan hasil praktik";
            } elseif ($final >= 80) {
                $materi = "memahami sebagian besar materi pembelajaran, pengerjaan tugas harian, serta terampil dalam menyelesaikan tugas praktis";
            } elseif ($final >= 70) {
                $materi = "memahami materi dasar, pengerjaan tugas harian, serta cukup terampil dalam pengerjaan praktik dasar";
            } else {
                $materi = "memahami konsep dasar materi pelajaran serta kedisiplinan dalam penyusunan tugas akademik dan praktik";
            }
            return "{$level} dalam {$materi} pada mata pelajaran {$assignment->subject->name}.";
        } else {
            return "{$level} dalam " . implode(', ', array_unique($topics)) . " pada mata pelajaran {$assignment->subject->name}.";
        }
    }

    /**
     * Ensure the logged-in teacher owns this assignment.
     */
    private function authorizeAssignment(ClassroomSubjectTeacher $assignment): void
    {
        $teacher = Auth::user()->teacher;
        abort_if(!$teacher || $assignment->teacher_id !== $teacher->id, 403);
    }
}
