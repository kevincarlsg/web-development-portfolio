<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AlumnoController extends Controller
{
    public function show($groupId)
    {
        // Obtener el usuario autenticado
        $alumno = Auth::user();

        // Verificar si el usuario tiene el rol de "alumno"
        if (!$alumno->isAlumno()) {
            return redirect()->back();
        }

        // Obtener el grupo por su ID
        $group = Group::find($groupId);

        // Verificar si el grupo existe y si el alumno pertenece a él
        if (!$group || !$alumno->groups->contains($groupId)) {
            return redirect()->back();
        }

        // Obtener las asistencias del alumno relacionadas con el grupo
        $attendances = $alumno->attendances()->where('group_id', $group->id)->get();

        // Calcular los datos para el gráfico de resumen de asistencias
        $attendanceCount = $attendances->where('status', 'on_time')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $lateACount = $attendances->where('status', 'late_a')->count();
        $lateBCount = $attendances->where('status', 'late_b')->count();

        // Pasar todos los datos necesarios a la vista
        return view('dashboard.alumno.show', compact('alumno', 'group', 'attendances', 'attendanceCount', 'absentCount', 'lateACount', 'lateBCount'));
    }
}
