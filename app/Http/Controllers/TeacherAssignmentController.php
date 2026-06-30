<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassroomSubjectTeacher;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherAssignmentController extends Controller
{
    /**
     * Daftar semua guru – halaman pemilih guru sebelum masuk ke penugasan.
     */
    public function allTeachers(Request $request)
    {
        $search = $request->input('search');

        $teachers = User::where('role', 'teacher')
            ->with('teacher')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $activeYear = AcademicYear::where('is_active', true)->first();

        return view('management.users.teacher.assignments.teachers', compact('teachers', 'search', 'activeYear'));
    }

    /**
     * HALAMAN DAFTAR: Menampilkan riwayat penugasan untuk satu guru tertentu.
     */
    public function index(Teacher $teacher)
    {
        $user       = $teacher->user;
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Mengambil riwayat penugasan yang dikelompokkan berdasarkan tahun ajaran
        $assignments = $teacher->assignments()
            ->with(['academicYear', 'classroom', 'subject'])
            ->get()
            ->groupBy('academic_year_id');

        return view('management.users.teacher.assignments.index', compact(
            'teacher', 'user', 'activeYear', 'assignments'
        ));
    }

    /**
     * HALAMAN FORM TAMBAH: Menampilkan form untuk memplot kelas & mapel baru.
     */
public function create(Teacher $teacher)
{
    $user       = $teacher->user;
    $activeYear = AcademicYear::where('is_active', true)->first();

    $academicYears = AcademicYear::orderByDesc('year')
        ->orderByDesc('semester')
        ->get();

    $classrooms = Classroom::orderBy('name')->get();

    $subjects = Subject::orderBy('name')->get();


    // Ambil kombinasi tahun ajaran + kelas + mapel yang sudah memiliki guru
    $usedAssignments = ClassroomSubjectTeacher::select('academic_year_id', 'classroom_id', 'subject_id')
        ->get()
        ->toArray();


    return view('management.users.teacher.assignments.create', compact(
        'teacher',
        'user',
        'activeYear',
        'academicYears',
        'classrooms',
        'subjects',
        'usedAssignments'
    ));
}

    /**
     * PROSES SIMPAN: Tambah penugasan guru ke kelas & mapel tertentu.
     */
    public function store(Request $request, string $teacherId)
    {
        $teacher = Teacher::findOrFail($teacherId);

        $request->validate([
            'classroom_id'     => 'required|exists:classrooms,id',
            'subject_id'       => 'required|exists:subjects,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        // Cek apakah kombinasi kelas+mapel+tahun ajaran sudah dipakai guru lain
        $existing = ClassroomSubjectTeacher::where('classroom_id',      $request->classroom_id)
            ->where('subject_id',       $request->subject_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.teachers.assignments.index', $teacher->id)
                ->with('error', 'Kombinasi kelas & mata pelajaran tersebut sudah diisi oleh guru lain pada tahun ajaran ini.');
        }

        // Cek apakah guru sudah punya penugasan yang sama persis
        $duplicate = ClassroomSubjectTeacher::where('teacher_id',       $teacher->id)
            ->where('classroom_id',     $request->classroom_id)
            ->where('subject_id',       $request->subject_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->first();

        if ($duplicate) {
            return redirect()
                ->route('admin.teachers.assignments.index', $teacher->id)
                ->with('error', 'Guru ini sudah memiliki penugasan yang sama.');
        }

        $assignment = ClassroomSubjectTeacher::create([
            'teacher_id'       => $teacher->id,
            'classroom_id'     => $request->classroom_id,
            'subject_id'       => $request->subject_id,
            'academic_year_id' => $request->academic_year_id,
        ]);

        $assignment->generateDefaultAssessments();

        return redirect()
            ->route('admin.teachers.assignments.index', $teacher->id)
            ->with('success', 'Penugasan berhasil ditambahkan.');
    }

    /**
     * PROSES HAPUS: Hapus penugasan guru dari kelas & mapel tertentu.
     */
    public function destroy(string $teacherId, string $assignmentId)
    {
        $teacher    = Teacher::findOrFail($teacherId);
        $assignment = ClassroomSubjectTeacher::where('id', $assignmentId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $assignment->delete();

        return redirect()
            ->route('admin.teachers.assignments.index', $teacher->id)
            ->with('success', 'Penugasan berhasil dihapus.');
    }
}
