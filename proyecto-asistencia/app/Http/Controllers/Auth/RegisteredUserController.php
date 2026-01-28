<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar los campos del formulario
        $request->validate([
            'name' => [
                'required',
                'string',
                // Permite de 1 a 4 palabras, cada una con entre 2 y 20 letras, incluyendo letras con acentos
                'regex:/^([a-zA-ZÁÉÍÓÚáéíóúÑñ]{2,20})(\s[a-zA-ZÁÉÍÓÚáéíóúÑñ]{2,20}){0,3}$/u',
                'max:255',
            ],
            'email' => 'required|string|email|max:255|unique:users,email', // Validación de correo único
            'password' => [
                'required',
                'confirmed',
                'min:8', // Longitud mínima de 8 caracteres
                'max:20', // Longitud máxima de 20 caracteres
                // Asegura al menos:
                // - Una letra mayúscula
                // - Una letra minúscula
                // - Un carácter especial, incluyendo el punto
                // - Un número
                'regex:/[A-Z]/', // Al menos una letra mayúscula
                'regex:/[a-z]/', // Al menos una letra minúscula
                'regex:/[0-9]/', // Al menos un número
                'regex:/[@$!%*#?&.]/', // Al menos un carácter especial o un punto
            ],
            'role' => 'required|in:alumno,profesor', // Validación de rol
        ]);

        // Crear el usuario con los datos validados
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Encriptar contraseña
            'role' => $request->role, // Asignar rol
        ]);

        // Desencadenar el evento de registro
        event(new Registered($user));

        // Redirigir al login con un mensaje de éxito
        return redirect()->route('login')->with('success', 'Usuario registrado correctamente.');
    }
}
