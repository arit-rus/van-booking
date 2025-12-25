<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        
        // Try LDAP authentication with samaccountname first
        // Then try with mail attribute
        // Finally fallback to local database auth with email
        $ldapAttempt = Auth::attempt([
            'samaccountname' => $credentials['email'],
            'password' => $credentials['password'],
        ], $this->boolean('remember'));
        
        if (!$ldapAttempt) {
            // Try with mail attribute
            $ldapAttempt = Auth::attempt([
                'mail' => $credentials['email'],
                'password' => $credentials['password'],
            ], $this->boolean('remember'));
        }
        
        if (!$ldapAttempt) {
            // Fallback to local auth (handled by fallback provider)
            $ldapAttempt = Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ], $this->boolean('remember'));
        }
        
        if (!$ldapAttempt) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Check if user exists in HRD database
        $user = Auth::user();
        if ($user && $user->idcard) {
            try {
                $hrdPerson = DB::connection('hrd')
                    ->table('v_hrd1_person')
                    ->where('person_id', $user->idcard)
                    ->first();

                if (!$hrdPerson) {
                    // User not found in HRD - delete user record, logout and deny access
                    $userId = $user->id;
                    Auth::logout();
                    $this->session()->invalidate();
                    $this->session()->regenerateToken();
                    
                    // Delete the user that was just created by LDAP sync
                    \App\Models\User::where('id', $userId)->delete();

                    throw ValidationException::withMessages([
                        'email' => 'ไม่พบข้อมูลในระบบทรัพยากรบุคคล กรุณาติดต่อเจ้าหน้าที่',
                    ]);
                }
            } catch (ValidationException $e) {
                throw $e;
            } catch (\Exception $e) {
                // HRD database connection error - allow login but log warning
                \Log::warning('HRD database check failed: ' . $e->getMessage());
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
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
