<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AcademicYearsController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\TeacherAssignmentController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Auth\AccountActivationController;
use App\Http\Controllers\KelasSayaController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SwitchRoleController;
use App\Http\Controllers\HomeroomController;
use App\Http\Controllers\AdminReportCardController;
use App\Http\Controllers\ParentDashboardController;
use App\Http\Controllers\HeadmasterController;
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Public Routes
// ──────────────────────────────────────────────

use App\Models\AcademicYear;

Route::get('/', function () {
    $activeYear = AcademicYear::where('is_active', true)->first();
    $releaseDate = $activeYear ? $activeYear->report_release_at : null;

    $allValidated = false;
    if ($activeYear) {
        $totalStudents = \App\Models\Student::has('classroom')->count();
        $validatedReports = \App\Models\ReportCard::where('academic_year_id', $activeYear->id)
            ->where('is_validated', true)
            ->count();
        $allValidated = ($totalStudents > 0) && ($validatedReports === $totalStudents);
    }

    return view('welcome', compact('activeYear', 'releaseDate', 'allValidated'));
});

// ──────────────────────────────────────────────
// Account Activation (public – no auth needed)
// ──────────────────────────────────────────────

Route::get('/activate/{token}', [AccountActivationController::class, 'showForm'])
    ->name('account.activate.form');

Route::post('/activate', [AccountActivationController::class, 'activate'])
    ->name('account.activate');

// ──────────────────────────────────────────────
// Dashboard Redirect (berdasarkan role)
// ──────────────────────────────────────────────

Route::get('/dashboard', function () {
    $role = session('active_role', auth()->user()->role);

    // Validate that the user actually has this role
    if (!auth()->user()->hasRole($role)) {
        $role = auth()->user()->role;
        session(['active_role' => $role]);
    }

    if (in_array($role, ['admin', 'teacher', 'parent', 'headmaster'])) {
        return redirect()->route($role . '.dashboard');
    }
    abort(403, 'Unauthorized role.');
})->middleware(['auth'])->name('dashboard');

Route::post('/switch-role', [SwitchRoleController::class, 'switch'])
    ->middleware(['auth'])
    ->name('switch-role');

// ──────────────────────────────────────────────
// Admin Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/management', function () {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        return view('management.index', compact('activeYear'));
    })->name('management.index');

    Route::post('/management/release-time', [AcademicYearsController::class, 'updateReleaseTime'])
        ->name('academic_years.update-release-time');

    Route::get('assignments', [TeacherAssignmentController::class, 'allTeachers'])->name('assignments.teachers');

    Route::get('teachers/{teacher}/assignments', [TeacherAssignmentController::class, 'index'])->name('teachers.assignments.index');

    Route::get('teachers/{teacher}/assignments/create', [TeacherAssignmentController::class, 'create'])->name('teachers.assignments.create');

    Route::post('teachers/{teacher}/assignments', [TeacherAssignmentController::class, 'store'])->name('teachers.assignments.store');
    Route::delete('teachers/{teacher}/assignments/{assignment}', [TeacherAssignmentController::class, 'destroy'])->name('teachers.assignments.destroy');
    // ===============================================

    // Data Master Resource
    Route::resource('teachers', TeacherController::class);
    Route::resource('parents', ParentsController::class);
    Route::resource('students', StudentController::class);
    Route::resource('classrooms', ClassroomController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('academic_years', AcademicYearsController::class);

    // Report Card Validation
    Route::get('report-cards', [AdminReportCardController::class, 'index'])->name('report-cards.index');
    Route::get('report-cards/classroom/{classroom}', [AdminReportCardController::class, 'showClassroom'])->name('report-cards.classroom');
    Route::post('report-cards/{reportCard}/validate', [AdminReportCardController::class, 'validateReport'])->name('report-cards.validate');
    Route::post('report-cards/{reportCard}/reject', [AdminReportCardController::class, 'rejectReport'])->name('report-cards.reject');
    Route::post('report-cards/classroom/{classroom}/validate-all', [AdminReportCardController::class, 'validateAll'])->name('report-cards.validate-all');
    Route::post('report-cards/promote-classes', [AdminReportCardController::class, 'promoteClasses'])->name('report-cards.promote-classes');
});

// ──────────────────────────────────────────────
// Teacher Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');

    Route::get('/kelas_saya', [KelasSayaController::class, 'index'])->name('kelas_saya.index');
    Route::get('/kelas_saya/kkm', [KelasSayaController::class, 'kkm'])->name('kelas_saya.kkm');
    Route::post('/kelas_saya/kkm/{subject}', [KelasSayaController::class, 'setKkm'])->name('kelas_saya.set-kkm');

    // Grade management routes
    Route::get('/assignments/{assignment}/grades', [GradesController::class, 'index'])->name('grades.index');
    Route::post('/assignments/{assignment}/grades', [GradesController::class, 'update'])->name('grades.update');

    // Laporan routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/{assignment}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::post('/laporan/{assignment}/save-draft', [LaporanController::class, 'saveDraft'])->name('laporan.save-draft');
    Route::post('/laporan/{assignment}/submit-final', [LaporanController::class, 'submitFinal'])->name('laporan.submit-final');
    Route::post('/laporan/{assignment}/cancel-submission', [LaporanController::class, 'cancelSubmission'])->name('laporan.cancel-submission');
    Route::post('/laporan/{assignment}/regenerate', [LaporanController::class, 'regenerate'])->name('laporan.regenerate');

    // Homeroom (Wali Kelas) Routes
    Route::get('/homeroom', [HomeroomController::class, 'index'])->name('homeroom.index');
    Route::post('/homeroom/generate-all', [HomeroomController::class, 'generateAll'])->name('homeroom.generate-all');
    Route::post('/homeroom/submit-all', [HomeroomController::class, 'submitAll'])->name('homeroom.submit-all');
    Route::get('/homeroom/student/{student}/pantau', [HomeroomController::class, 'pantau'])->name('homeroom.student.pantau');
    Route::get('/homeroom/student/{student}/input', [HomeroomController::class, 'input'])->name('homeroom.student.input');
    Route::post('/homeroom/student/{student}/store', [HomeroomController::class, 'store'])->name('homeroom.student.store');
    Route::post('/homeroom/student/{student}/generate', [HomeroomController::class, 'generate'])->name('homeroom.student.generate');
    Route::post('/homeroom/student/{student}/submit', [HomeroomController::class, 'submit'])->name('homeroom.student.submit');
    Route::post('/homeroom/student/{student}/cancel', [HomeroomController::class, 'cancel'])->name('homeroom.student.cancel');
});

// Parent Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/academic',  [ParentDashboardController::class, 'academic'])->name('academic');
    Route::get('/academic/subject/{cstId}', [ParentDashboardController::class, 'academicSubject'])->name('academic.subject');
    Route::get('/attendance',[ParentDashboardController::class, 'attendance'])->name('attendance');
    Route::get('/report',    [ParentDashboardController::class, 'report'])->name('report');
    Route::get('/report/{reportCard}', [ParentDashboardController::class, 'reportView'])->name('report.view');
});

// ──────────────────────────────────────────────
// Headmaster Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:headmaster'])->prefix('headmaster')->name('headmaster.')->group(function () {
    Route::get('/dashboard', [HeadmasterController::class, 'index'])->name('dashboard');
    Route::get('/akademik',  [HeadmasterController::class, 'akademik'])->name('akademik');
    Route::get('/kehadiran', [HeadmasterController::class, 'kehadiran'])->name('kehadiran');

    // Pengesahan Rapor
    Route::get('/pengesahan', [HeadmasterController::class, 'pengesahanIndex'])->name('pengesahan.index');
    Route::get('/pengesahan/kelas/{classroom}', [HeadmasterController::class, 'pengesahanKelas'])->name('pengesahan.kelas');
    Route::post('/pengesahan/{reportCard}/validate', [HeadmasterController::class, 'validateReport'])->name('pengesahan.validate');
    Route::post('/pengesahan/{reportCard}/reject', [HeadmasterController::class, 'rejectReport'])->name('pengesahan.reject');
    Route::post('/pengesahan/kelas/{classroom}/validate-all', [HeadmasterController::class, 'validateAll'])->name('pengesahan.validate-all');
});

// ──────────────────────────────────────────────
// Profile Routes (semua role yang sudah login)
// ──────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/edit', [ProfileController::class, 'editDetails'])->name('profile.edit-details');
    Route::get('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
