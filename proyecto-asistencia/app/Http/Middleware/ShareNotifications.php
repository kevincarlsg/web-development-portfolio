<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class ShareNotifications
{
    public function handle(Request $request, Closure $next)
    {
        // Solo compartir las notificaciones si el usuario está autenticado
        if (Auth::check()) {
            $notifications = Notification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
            view()->share('notifications', $notifications);
        }

        return $next($request);
    }
}
