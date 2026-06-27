<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomSubjectTeacher;
use App\Models\Subject;
use App\Models\User;
use App\Models\Teacher;
use App\Mail\AccountActivationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query Data Guru
        $teachers = User::where('role', 'teacher')
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('management.users.teacher.index', compact('teachers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('management.users.teacher.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'  => 'required|email|unique:users,email',
            'name'   => 'required|string|max:255',
            'nip'    => 'required|string|max:16',
            'gender' => 'required|in:L,P',
            'telp'   => 'required|string|max:16',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'role'     => 'teacher',
                'password' => null,
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'nip'     => $request->nip,
                'gender'  => $request->gender,
                'telp'    => $request->telp,
            ]);

            // Buat token aktivasi dan simpan ke password_reset_tokens
            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token'      => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            // Buat URL aktivasi
            $activationUrl = url(route('account.activate.form', [
                'token' => $token,
                'email' => $user->email,
            ], false));

            // Kirim email aktivasi
            Mail::to($user->email)->send(new AccountActivationMail($user, $activationUrl));
        });

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Akun guru berhasil dibuat. Email aktivasi telah dikirim ke ' . $request->email . '.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('teacher')->where('role', 'teacher')->findOrFail($id);

        // Data untuk form penugasan
        $classrooms    = Classroom::orderBy('name')->get();
        $subjects      = Subject::orderBy('name')->get();
        $academicYears = AcademicYear::orderByDesc('year')->orderByDesc('semester')->get();
        $activeYear    = AcademicYear::where('is_active', true)->first();

        // Ambil semua penugasan guru ini, dikelompokkan per tahun ajaran
        $assignments = collect();
        if ($user->teacher) {
            $assignments = ClassroomSubjectTeacher::where('teacher_id', $user->teacher->id)
                ->with(['classroom', 'subject', 'academicYear'])
                ->orderByDesc('academic_year_id')
                ->get()
                ->groupBy('academic_year_id');
        }

        return view('management.users.teacher.show', compact(
            'user', 'classrooms', 'subjects', 'academicYears', 'activeYear', 'assignments'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::where('role', 'teacher')->findOrFail($id);
        return view('management.users.teacher.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('role', 'teacher')->findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'nip'    => 'required|string|max:16',
            'gender' => 'required|in:L,P',
            'telp'   => 'required|string|max:16',
        ]);

        $oldEmail = $user->email;
        $emailChanged = $oldEmail !== $request->email;

        DB::transaction(function () use ($request, $user, $oldEmail, $emailChanged) {
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            $user->teacher->update([
                'nip'    => $request->nip,
                'gender' => $request->gender,
                'telp'   => $request->telp,
            ]);

            if ($emailChanged) {
                // Reset password dan email_verified_at agar akun dinonaktifkan sementara sampai diaktivasi ulang
                $user->update([
                    'password' => null,
                    'email_verified_at' => null,
                ]);

                // Hapus token lama jika ada
                DB::table('password_reset_tokens')->where('email', $oldEmail)->delete();

                // Buat token aktivasi baru
                $token = Str::random(64);
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'token'      => Hash::make($token),
                        'created_at' => now(),
                    ]
                );

                // Buat URL aktivasi
                $activationUrl = url(route('account.activate.form', [
                    'token' => $token,
                    'email' => $user->email,
                ], false));

                // Kirim email aktivasi
                Mail::to($user->email)->send(new AccountActivationMail($user, $activationUrl));
            }
        });

        if ($emailChanged) {
            return redirect()
                ->route('admin.teachers.index')
                ->with('success', 'Guru berhasil diperbarui. Email aktivasi baru telah dikirim ke ' . $request->email . '.');
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::where('role', 'teacher')->findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->teacher()->delete();
            $user->delete();
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus');
    }
}
