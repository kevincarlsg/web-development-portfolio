<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Elimina todas las notificaciones del usuario autenticado.
     *
     * @return \Illuminate\Http\Response
     */
    public function clear()
    {
        // Eliminar todas las notificaciones del usuario autenticado
        Notification::where('user_id', Auth::id())->delete();

        // Redirigir de regreso con un mensaje de éxito
        return redirect()->back()->with('success', 'Notificaciones eliminadas correctamente.');
    }
    
    /**
     * Muestra todas las notificaciones para el usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todas las notificaciones del usuario autenticado
        $notifications = Notification::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();

        return view('notifications.index', compact('notifications'));
    }
}
