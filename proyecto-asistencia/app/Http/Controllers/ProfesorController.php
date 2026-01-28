<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Group;
use App\Models\Attendance;
use App\Models\Justificante;
use App\Models\User;

class ProfesorController extends Controller
{
    // Método index que redirige al dashboard del profesor
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si el usuario tiene el rol de profesor
        if (!$user->isProfesor()) {
            return redirect('/')->with('error', 'No tiene permisos para acceder al dashboard de profesor.');
        }

        // Obtener los grupos asignados al profesor
        $groups = Group::where('profesor_id', $user->id)->get();

        // Lógica para obtener los alumnos en riesgo (basado en el número de faltas)
        $alumnosEnRiesgo = User::whereHas('attendances', function ($query) {
            $query->where('status', 'falta');
        })
        ->withCount(['attendances as faltas' => function ($query) {
            $query->where('status', 'falta');
        }])
        ->orderByDesc('faltas')
        ->take(5) // Limitar a los 5 alumnos con más faltas
        ->get();

        // Obtener justificantes pendientes
        $justificantes = Justificante::where('estado', 'pendiente')->with('alumno')->get();

        // Retornar la vista del dashboard del profesor y pasar los datos
        return view('dashboard-profesor', [
            'groups' => $groups,
            'alumnosEnRiesgo' => $alumnosEnRiesgo,
            'justificantes' => $justificantes,
        ]);
    }

    // ProfesorController.php

    public function getAttendanceDataBySession($sessionId)
    {
        // Obtener los totales de cada estado de asistencia para la sesión seleccionada
        $attendanceCount = Attendance::where('session_id', $sessionId)->where('status', 'on_time')->count();
        $absentCount = Attendance::where('session_id', $sessionId)->where('status', 'absent')->count();
        $lateACount = Attendance::where('session_id', $sessionId)->where('status', 'late_a')->count();
        $lateBCount = Attendance::where('session_id', $sessionId)->where('status', 'late_b')->count();

        // Retornar los datos en formato JSON
        return response()->json([
            'attendance' => $attendanceCount,
            'absent' => $absentCount,
            'late_a' => $lateACount,
            'late_b' => $lateBCount,
        ]);
}

}
