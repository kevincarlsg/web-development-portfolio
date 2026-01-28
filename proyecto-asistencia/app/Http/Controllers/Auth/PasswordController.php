<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Validaciones para la contraseña actual y nueva
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'], // Verifica que la contraseña actual sea correcta
            'password' => [
                'required',
                'string',
                'min:8',                 // Al menos 8 caracteres
                'confirmed',              // Debe coincidir con el campo de confirmación
                Password::min(8)          // Usar la validación de contraseña por defecto
                    ->mixedCase()         // Debe incluir letras mayúsculas y minúsculas
                    ->numbers()           // Debe incluir al menos un número
                    ->symbols()           // Debe incluir al menos un símbolo (carácter especial)
            ],
        ]);

        // Actualizar la contraseña del usuario
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redirigir con un mensaje de éxito
        return back()->with('status', 'Contraseña actualizada correctamente.');
    }
}
