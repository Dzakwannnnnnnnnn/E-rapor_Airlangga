<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\ClassroomSubjectTeacher;
use App\Models\GradeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradesController extends Controller
{
    /**
     * Tampilkan halaman input nilai: daftar siswa + kolom per assessment.
     */
    public function index(ClassroomSubjectTeacher $assignment)
    {
        // Make sure the logged-in teacher owns this assignment
        $teacher = Auth::user()->teacher;
        abort_if(!$teacher || $assignment->teacher_id !== $teacher->id, 403);

        $assignment->load([
            'classroom.students',
            'subject',
            'academicYear',
            'assessments' => fn ($q) => $q->orderBy('type')->orderBy('sequence'),
        ]);

        $classroom   = $assignment->classroom;
        $students    = $classroom->students->sortBy('name');
        $assessments = $assignment->assessments;

        // Group assessments by type for the header
        $uhAssessments    = $assessments->where('type', 'uh')->values();
        $tugasAssessments = $assessments->where('type', 'tugas')->values();
        $pasAssessments   = $assessments->where('type', 'pas')->values();

        // Pre-load all grade entries for these assessments & students
        $assessmentIds = $assessments->pluck('id');
        $studentIds    = $students->pluck('id');

        $gradeEntriesMap = GradeEntry::whereIn('assessment_id', $assessmentIds)
            ->whereIn('student_id', $studentIds)
            ->get()
            ->keyBy(fn ($ge) => "{$ge->assessment_id}_{$ge->student_id}");

        return view('kelas_saya.grades.index', compact(
            'assignment', 'classroom', 'students',
            'assessments', 'uhAssessments', 'tugasAssessments', 'pasAssessments',
            'gradeEntriesMap'
        ));
    }

    /**
     * Simpan semua nilai: bulk upsert grade_entries.
     */
    public function update(Request $request, ClassroomSubjectTeacher $assignment)
    {
        $teacher = Auth::user()->teacher;
        abort_if(!$teacher || $assignment->teacher_id !== $teacher->id, 403);

        $scores = $request->input('scores', []); // scores[assessment_id][student_id] = nilai

        DB::transaction(function () use ($scores) {
            foreach ($scores as $assessmentId => $studentScores) {
                foreach ($studentScores as $studentId => $score) {
                    GradeEntry::updateOrCreate(
                        [
                            'assessment_id' => (int) $assessmentId,
                            'student_id'    => (int) $studentId,
                        ],
                        [
                            'score' => is_numeric($score) ? (float) $score : null,
                        ]
                    );
                }
            }
        });

        return redirect()
            ->route('teacher.grades.index', $assignment->id)
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
