<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileAlumnoController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit_alumno', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information, including password change and profile photo.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        try {
            // Validar los campos del formulario
            $validatedData = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'regex:/^(\p{L}{2,20})(\s\p{L}{2,20}){0,3}$/u', // 2-20 letras por palabra, máximo 4 palabras
                    'max:255',
                ],
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // Máximo 10MB
            ], [
                'name.regex' => 'El nombre debe tener entre 2 y 20 letras por palabra, con un máximo de 4 palabras.',
                'photo.image' => 'El archivo debe ser una imagen.',
                'photo.mimes' => 'Solo se permiten archivos de tipo jpg, jpeg o png.',
                'photo.max' => 'La imagen no debe exceder los 10 MB.',
            ]);

            // Actualizar el nombre y el correo electrónico
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            // Manejar la foto de perfil
            if ($request->hasFile('photo')) {
                // Eliminar la foto existente si hay una
                if ($user->photo && file_exists(base_path('../public_html/' . $user->photo))) {
                    unlink(base_path('../public_html/' . $user->photo));
                }

                // Guardar la nueva foto
                $destinationPath = base_path('../public_html/photos');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $filename = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
                $path = $request->file('photo')->move($destinationPath, $filename);
                $user->photo = 'photos/' . basename($path);
            }

            // Procesar cambio de contraseña
            if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
                $passwordData = $request->validate([
                    'current_password' => ['required', 'current_password'],
                    'new_password' => [
                        'required_with:current_password',
                        'string',
                        'min:8',
                        'max:20',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*().]).+$/',
                        'confirmed',
                    ],
                ], [
                    'current_password.required' => 'Debe ingresar su contraseña actual.',
                    'current_password.current_password' => 'La contraseña actual no es correcta.',
                    'new_password.required_with' => 'Debe ingresar una nueva contraseña.',
                    'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
                    'new_password.max' => 'La nueva contraseña no puede tener más de 20 caracteres.',
                    'new_password.regex' => 'La nueva contraseña debe incluir al menos una letra mayúscula, una minúscula, un número y un carácter especial.',
                    'new_password.confirmed' => 'La confirmación de la contraseña no coincide.',
                ]);

                $user->password = Hash::make($passwordData['new_password']);
            }

            // Guardar los cambios en la base de datos
            $user->save();

            return Redirect::route('profile.edit_alumno')->with('status', 'Perfil actualizado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación y redirigir con errores
            return Redirect::route('profile.edit_alumno')->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error al actualizar el perfil: ' . $e->getMessage());
            return Redirect::route('profile.edit_alumno')->withErrors('Ocurrió un error inesperado al actualizar el perfil. Inténtalo de nuevo.');
        }
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->photo && file_exists(public_path($user->photo))) {
            try {
                unlink(public_path($user->photo));
                $user->photo = null;
                $user->save();
                return Redirect::route('profile.edit_alumno')->with('status', 'Foto de perfil eliminada correctamente.');
            } catch (\Exception $e) {
                Log::error('Error al eliminar la foto de perfil: ' . $e->getMessage());
                return Redirect::route('profile.edit_alumno')->withErrors('Ocurrió un error al eliminar la foto de perfil. Inténtalo de nuevo.');
            }
        }

        return Redirect::route('profile.edit_alumno')->with('status', 'No se encontró ninguna foto de perfil para eliminar.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('status', 'Cuenta eliminada correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar la cuenta: ' . $e->getMessage());
            return Redirect::route('profile.edit_alumno')->withErrors('Ocurrió un error al eliminar la cuenta. Inténtalo de nuevo.');
        }
    }
}
