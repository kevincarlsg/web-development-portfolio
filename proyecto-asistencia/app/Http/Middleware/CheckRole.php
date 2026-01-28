<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class CheckRole
{

    public function handle(Request $request, Closure $next, $role)
    {
        // Log::info('CheckRole invocado con role: ' . $role);
    
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }
    
        return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta página.');
    }
    
}
