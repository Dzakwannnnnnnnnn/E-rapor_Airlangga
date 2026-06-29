<?php

namespace App\Http\Requests\Auth;

use App\Models\Student;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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

        // Only require the captcha response field if a sitekey is configured.
        if (! app()->runningUnitTests() && config('captcha.sitekey')) {
            $rules['g-recaptcha-response'] = ['required', 'string'];
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
        ];
    }

    /**
     * Run additional validation after the standard rules pass.
     * Performs manual Google reCAPTCHA v2 verification by calling the
     * siteverify API directly — avoids relying on the no-captcha package's
     * Guzzle dependency which can fail on some hosting environments.
     */
    public function withValidator($validator): void
    {
        if (app()->runningUnitTests() || ! config('captcha.secret')) {
            return;
        }

        $validator->after(function ($validator) {
            $token    = $this->input('g-recaptcha-response');
            $secret   = config('captcha.secret');
            $remoteIp = $this->ip();

            if (empty($token)) {
                return; // 'required' rule already handles this
            }

            try {
                $response = Http::asForm()->post(
                    'https://www.google.com/recaptcha/api/siteverify',
                    [
                        'secret'   => $secret,
                        'response' => $token,
                        'remoteip' => $remoteIp,
                    ]
                );

                $result = $response->json();

                if (! ($result['success'] ?? false)) {
                    $validator->errors()->add(
                        'g-recaptcha-response',
                        'Verifikasi Captcha gagal. Silakan coba lagi.'
                    );
                }
            } catch (\Throwable $e) {
                // If we cannot reach Google's API, log it but do NOT block the user.
                // This prevents a Google outage from locking everyone out.
                \Illuminate\Support\Facades\Log::warning('reCAPTCHA verification failed to contact Google API: ' . $e->getMessage());
            }
        });
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

        if (! $student || $student->parents()->count() === 0) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'NISN tidak ditemukan atau belum terhubung ke akun orang tua.',
            ]);
        }

        // Get the parent user accounts (relationship set by admin)
        $parents = $student->parents()->with('user')->get();
        $authenticatedUser = null;

        foreach ($parents as $parent) {
            if ($parent->user && Hash::check($password, $parent->user->password)) {
                $authenticatedUser = $parent->user;
                break;
            }
        }

        if (! $authenticatedUser) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'NISN atau kata sandi yang dimasukkan tidak sesuai.',
            ]);
        }

        // Verify that the account has been activated (email verified)
        if (! $authenticatedUser->email_verified_at) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'email' => 'Akun orang tua belum diaktifkan. Silakan cek email untuk aktivasi.',
            ]);
        }

        // Log in the parent user
        Auth::login($authenticatedUser, $this->boolean('remember'));
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
