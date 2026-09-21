<?php

namespace App\Http\Requests\Auth;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            ActivityLog::log(
                aksi: 'LOGIN_FAILED',
                entitasTipe: 'USER',
                keterangan: ['email' => $this->string('email')->toString(), 'alasan' => 'Email atau password salah.'],
                ip: $this->ip()
            );

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();

        // Validasi status akun (PRD AUTH-03 & DESIGN.md 11)
        if ($user->status !== 'aktif') {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            $pesan = match ($user->status) {
                'pending' => 'Akun Anda menunggu verifikasi Admin.',
                'ditolak' => 'Pendaftaran akun Anda ditolak oleh Admin.'.($user->rejection_reason ? ' Alasan: '.$user->rejection_reason : ''),
                'nonaktif' => 'Akun Anda dinonaktifkan. Hubungi Admin.',
                default => 'Akun Anda tidak aktif.',
            };

            ActivityLog::log(
                aksi: 'LOGIN_BLOCKED',
                entitasTipe: 'USER',
                entitasId: $user->id,
                keterangan: ['email' => $user->email, 'status' => $user->status, 'pesan' => $pesan],
                userId: $user->id,
                ip: $this->ip()
            );

            throw ValidationException::withMessages([
                'email' => $pesan,
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        ActivityLog::log(
            aksi: 'LOGIN',
            entitasTipe: 'USER',
            entitasId: $user->id,
            keterangan: ['email' => $user->email, 'peran' => $user->role],
            userId: $user->id,
            ip: $this->ip()
        );
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
