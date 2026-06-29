<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Parents;
use App\Models\Student;
use App\Mail\AccountActivationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ParentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Query Data Wali Murid
        $parents = User::where('role', 'parent')
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('management.users.parent.index', compact('parents', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::with(['parents.user', 'classroom'])->orderBy('name')->get();
        return view('management.users.parent.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'       => 'required|email|unique:users,email',
            'name'        => 'required|string|max:255',
            'telp'        => 'required|string|max:16',
            'relation'    => 'required|in:ayah,ibu,wali',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        if ($request->has('student_ids')) {
            foreach ($request->student_ids as $studentId) {
                $student = Student::with('parents')->findOrFail($studentId);
                if ($student->parents->count() >= 2) {
                    return back()->withErrors(['student_ids' => "Siswa {$student->name} sudah memiliki maksimal 2 orang tua."])->withInput();
                }
                foreach ($student->parents as $p) {
                    if ($p->relation === $request->relation) {
                        return back()->withErrors(['student_ids' => "Siswa {$student->name} sudah memiliki wali murid dengan hubungan " . ucfirst($request->relation) . "."])->withInput();
                    }
                }
            }
        }

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'role'     => 'parent',
                'password' => null,
            ]);

            $parent = Parents::create([
                'user_id'  => $user->id,
                'telp'     => $request->telp,
                'relation' => $request->relation,
            ]);

            if ($request->has('student_ids')) {
                $parent->students()->attach($request->student_ids);
            }

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
            ->route('admin.parents.index')
            ->with('success', 'Akun wali murid berhasil dibuat. Email aktivasi telah dikirim ke ' . $request->email . '.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with(['parent.students.classroom'])->where('role', 'parent')->findOrFail($id);
        return view('management.users.parent.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with('parent')->where('role', 'parent')->findOrFail($id);
        $students = Student::with(['parents.user', 'classroom'])->orderBy('name')->get();
        return view('management.users.parent.edit', compact('user', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('role', 'parent')->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'telp'        => 'required|string|max:16',
            'relation'    => 'required|in:ayah,ibu,wali',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        if ($request->has('student_ids')) {
            foreach ($request->student_ids as $studentId) {
                // exclude current parent during validation
                $student = Student::with(['parents' => function($q) use ($user) {
                    $q->where('parents.id', '!=', $user->parent->id);
                }])->findOrFail($studentId);

                if ($student->parents->count() >= 2) {
                    return back()->withErrors(['student_ids' => "Siswa {$student->name} sudah memiliki maksimal 2 orang tua."])->withInput();
                }
                foreach ($student->parents as $p) {
                    if ($p->relation === $request->relation) {
                        return back()->withErrors(['student_ids' => "Siswa {$student->name} sudah memiliki wali murid dengan hubungan " . ucfirst($request->relation) . "."])->withInput();
                    }
                }
            }
        }

        $oldEmail = $user->email;
        $emailChanged = $oldEmail !== $request->email;

        DB::transaction(function () use ($request, $user, $oldEmail, $emailChanged) {
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            $user->parent->update([
                'telp'     => $request->telp,
                'relation' => $request->relation,
            ]);

            // Sync new associations
            $user->parent->students()->sync($request->input('student_ids', []));

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
                ->route('admin.parents.index')
                ->with('success', 'Wali Murid berhasil diperbarui. Email aktivasi baru telah dikirim ke ' . $request->email . '.');
        }

        return redirect()->route('admin.parents.index')->with('success', 'Wali Murid berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::where('role', 'parent')->findOrFail($id);

        DB::transaction(function () use ($user) {
            if ($user->parent) {
                $user->parent->students()->detach();
                $user->parent()->delete();
            }
            $user->delete();
        });

        return redirect()->route('admin.parents.index')->with('success', 'Wali Murid berhasil dihapus');
    }
}
