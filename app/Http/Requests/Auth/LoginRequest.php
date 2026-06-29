<?php

namespace App\Http\Requests\Auth;

use App\Models\Student;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Detect if the input looks like a NISN (numeric-only, up to 10 digits).
     */
    protected function isNisn(): bool
    {
        return preg_match('/^\d{1,10}$/', $this->string('email'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Accept email OR NISN (digits only) in the same "email" field.
        $rules = [
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        if (! app()->runningUnitTests()) {
            $rules['g-recaptcha-response'] = ['required', 'captcha'];
        }

        return $rules;
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'Email atau NISN wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'g-recaptcha-response.required' => 'Harap verifikasi bahwa Anda bukan robot.',
            'g-recaptcha-response.captcha'  => 'Verifikasi Captcha gagal. Silakan coba lagi.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * - If input looks like a NISN (digits only): find the student's parent user
     *   and verify their password. The parent–student relationship is defined
     *   entirely by the admin — this method only reads that existing relationship.
     * - Otherwise: standard email + password authentication (admin / teacher).
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if ($this->isNisn()) {
            $this->authenticateAsParent();
        } else {
            $this->authenticateWithEmail();
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Authenticate a parent using the NISN of their linked student.
     * The admin is responsible for linking students to parent accounts.
     *
     * @throws ValidationException
     */
    protected function authenticateAsParent(): void
    {
        $nisn = $this->string('email');
        $password = $this->string('password');

        // Find the student by NISN
        $student = Student::where('nisn', $nisn)->first();

        if (! $student || ! $student->parent_id) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'NISN tidak ditemukan atau belum terhubung ke akun orang tua.',
            ]);
        }

        // Get the parent's user account (relationship set by admin)
        $parent = $student->parent()->with('user')->first();

        if (! $parent || ! $parent->user) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Akun orang tua untuk siswa ini belum tersedia. Hubungi administrator.',
            ]);
        }

        $user = $parent->user;

        // Verify that the account has been activated (email verified)
        if (! $user->email_verified_at) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Akun orang tua belum diaktifkan. Silakan cek email untuk aktivasi.',
            ]);
        }

        // Verify password against the parent user's stored hash
        if (! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'NISN atau kata sandi yang dimasukkan tidak sesuai.',
            ]);
        }

        // Log in the parent user
        Auth::login($user, $this->boolean('remember'));
    }

    /**
     * Standard email + password authentication for admin and teachers.
     *
     * @throws ValidationException
     */
    protected function authenticateWithEmail(): void
    {
        // Validate that the input is actually an email format
        if (! filter_var($this->string('email'), FILTER_VALIDATE_EMAIL)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Format email tidak valid. Wali Murid gunakan NISN anak.',
            ]);
        }

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
