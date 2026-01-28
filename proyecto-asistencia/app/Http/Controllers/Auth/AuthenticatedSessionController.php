<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validar las credenciales
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            // Si las credenciales no son correctas, devolver con un mensaje de error
            return back()->withErrors([
                'email' => 'El correo o la contraseña son incorrectos. Por favor, vuelve a intentarlo.',
            ])->onlyInput('email');
        }

        // Regenerar la sesión para prevenir ataques de fijación de sesión
        $request->session()->regenerate();

        // Redirigir al dashboard u otra ruta
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout(); // Cerrar sesión
    
        $request->session()->invalidate(); // Invalidar la sesión
    
        $request->session()->regenerateToken(); // Regenerar el token CSRF
    
        return redirect('/login'); // Redirigir al login
    }
}
