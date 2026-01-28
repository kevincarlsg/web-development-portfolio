<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileProfesorController extends Controller
{
    /**
     * Mostrar el formulario del perfil del profesor.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit_profesor', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualizar la información del perfil del profesor, incluyendo la foto de perfil y la contraseña.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        try {
            // Validar los campos del perfil y la imagen de perfil
            $validatedData = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'regex:/^(\p{L}{2,20})(\s\p{L}{2,20}){0,3}$/u',
                    'max:255'
                ],
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            ], [
                'name.regex' => 'El nombre debe tener entre 2 y 20 letras por palabra, con un máximo de 4 palabras.',
                'photo.image' => 'El archivo debe ser una imagen.',
                'photo.mimes' => 'Solo se permiten archivos de tipo jpg, jpeg o png.',
                'photo.max' => 'La imagen no debe exceder los 10 MB.',
            ]);

            // Manejar la subida de la foto de perfil
            if ($request->hasFile('photo')) {
                if ($user->photo && file_exists(base_path('../public_html/' . $user->photo))) {
                    unlink(base_path('../public_html/' . $user->photo));
                }

                $destinationPath = base_path('../public_html/photos');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                $filename = uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
                $path = $request->file('photo')->move($destinationPath, $filename);
                $user->photo = 'photos/' . basename($path);
            }

            $user->fill($validatedData);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
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

            $user->save();

            return Redirect::route('profesor.profile.edit')->with('status', 'Perfil actualizado correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación y redirigir con errores específicos
            return Redirect::route('profesor.profile.edit')->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('Error al actualizar el perfil: ' . $e->getMessage());
            return Redirect::route('profesor.profile.edit')->withErrors('Ocurrió un error inesperado al actualizar el perfil. Inténtalo de nuevo.');
        }
    }

    /**
     * Eliminar la foto de perfil del profesor.
     */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->photo) {
            try {
                Storage::delete('public/' . $user->photo);
                $user->photo = null;
                $user->save();
                return Redirect::route('profesor.profile.edit')->with('status', 'Foto de perfil eliminada correctamente.');
            } catch (\Exception $e) {
                Log::error('Error al eliminar la foto de perfil: ' . $e->getMessage());
                return Redirect::route('profesor.profile.edit')->withErrors('Ocurrió un error al eliminar la foto de perfil. Inténtalo de nuevo.');
            }
        }

        return Redirect::route('profesor.profile.edit')->with('status', 'No se encontró ninguna foto de perfil para eliminar.');
    }

    /**
     * Eliminar la cuenta del profesor.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            if ($user->photo) {
                Storage::delete('public/' . $user->photo);
            }

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('status', 'Cuenta eliminada correctamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar la cuenta: ' . $e->getMessage());
            return Redirect::route('profesor.profile.edit')->withErrors('Ocurrió un error al eliminar la cuenta. Inténtalo de nuevo.');
        }
    }
}
