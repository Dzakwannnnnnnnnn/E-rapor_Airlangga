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
use Illuminate\Support\Facades\Route;

// ──────────────────────────────────────────────
// Public Routes
// ──────────────────────────────────────────────

Route::get('/', function () {
    return view('welcome');
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
    $role = auth()->user()->role;
    if (in_array($role, ['admin', 'teacher', 'parent'])) {
        return redirect()->route($role . '.dashboard');
    }
    abort(403, 'Unauthorized role.');
})->middleware(['auth'])->name('dashboard');

// ──────────────────────────────────────────────
// Admin Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/management', function () {
        return view('management.index');
    })->name('management.index');

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
});

// ──────────────────────────────────────────────
// Teacher Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('dashboard');

    Route::get('/kelas_saya', [KelasSayaController::class, 'index'])->name('kelas_saya.index');

    // Grade management routes
    Route::get('/assignments/{assignment}/grades', [GradesController::class, 'index'])->name('grades.index');
    Route::post('/assignments/{assignment}/grades', [GradesController::class, 'update'])->name('grades.update');

});

// ──────────────────────────────────────────────
// Parent Routes
// ──────────────────────────────────────────────

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', function () {
        return view('parent.dashboard');
    })->name('dashboard');

    // Tambahkan resource controller orang tua di sini:
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
