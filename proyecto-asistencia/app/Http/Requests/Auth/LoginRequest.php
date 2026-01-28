<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'], // Validación extra para longitud máxima
            'password' => ['required', 'string', 'min:6'], // Se asegura un mínimo de 6 caracteres en la contraseña
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited(); // Verificar si el usuario está bloqueado por intentos fallidos

        // Intentar autenticación
        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey()); // Incrementar el contador de intentos fallidos

            // Lanzar excepción con un mensaje de error
            throw ValidationException::withMessages([
                'email' => __('auth.failed'), // Mensaje estándar en `resources/lang`
            ]);
        }

        // Limpiar el contador si la autenticación es exitosa
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // Verificar si los intentos exceden el límite permitido (5 en este caso)
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this)); // Lanzar evento de bloqueo

        $seconds = RateLimiter::availableIn($this->throttleKey()); // Tiempo restante para desbloqueo

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
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
        // Usar el email e IP del usuario como clave única para limitar intentos
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}
