<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AccountActivationController extends Controller
{
    /**
     * Tampilkan form set password saat aktivasi.
     */
    public function showForm(Request $request, string $token)
    {
        $email = $request->query('email');

        // Cek apakah token valid di tabel password_reset_tokens
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$record || !Hash::check($token, $record->token)) {
            return view('auth.activate-invalid');
        }

        // Cek apakah token belum kedaluwarsa (24 jam)
        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if ($createdAt->diffInHours(now()) >= 24) {
            // Hapus token yang sudah expired
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return view('auth.activate-expired');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            abort(404);
        }

        return view('auth.activate', compact('token', 'email', 'user'));
    }

    /**
     * Simpan password baru dan aktifkan akun.
     */
    public function activate(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email|exists:users,email',
            'password'              => [
                'required',
                'confirmed',
                'min:8',
                'max:16',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).+$/',
            ],
            'password_confirmation' => 'required',
        ], [
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.min'       => 'Kata sandi minimal harus 8 karakter.',
            'password.max'       => 'Kata sandi maksimal tidak boleh lebih dari 16 karakter.',
            'password.regex'     => 'Kata sandi harus mengandung huruf kecil, huruf besar, angka, dan simbol.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Cek token
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'Link aktivasi tidak valid atau sudah digunakan.']);
        }

        $createdAt = \Carbon\Carbon::parse($record->created_at);
        if ($createdAt->diffInHours(now()) >= 24) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'Link aktivasi sudah kedaluwarsa. Hubungi administrator.']);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Set password dan tandai email verified
        $user->update([
            'password'          => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        // Hapus token aktivasi
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with(
            'status',
            'Akun Anda berhasil diaktifkan! Silakan masuk dengan kata sandi yang baru dibuat.'
        );
    }
}
