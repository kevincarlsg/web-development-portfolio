<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\Justificante;
use App\Models\User;
use App\Models\Group; // IMPORTAR EL MODELO GROUP
use Carbon\Carbon;



class DashboardController extends Controller
{
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Comprobar el rol del usuario y redirigir al dashboard correspondiente
        if ($user->isProfesor()) {
            return $this->profesorDashboard();
        } elseif ($user->isAlumno()) {
            return $this->alumnoDashboard();
        } else {
            return redirect('/')->with('error', 'No tiene permisos para acceder al dashboard.');
        }
    }

   public function profesorDashboard()
{
    // Obtener el ID del profesor autenticado
    $profesorId = Auth::id();

    // Obtener los alumnos con faltas (absent) en los grupos del profesor, incluyendo el conteo de faltas
    $alumnosConFaltas = Attendance::select('user_id')
        ->selectRaw('COUNT(*) as faltas')
        ->where('status', 'absent') // Filtrar por 'absent'
        ->whereHas('group', function ($query) use ($profesorId) {
            $query->where('profesor_id', $profesorId);
        })
        ->groupBy('user_id')
        ->with('user')
        ->get();

    // Verificar si hay alumnos con faltas
    if ($alumnosConFaltas->isNotEmpty()) {
        // Obtener el número máximo de faltas entre los alumnos
        $maxFaltas = $alumnosConFaltas->max('faltas');

        // Filtrar los alumnos que tienen el número máximo de faltas
        $alumnosMaxFaltas = $alumnosConFaltas->filter(function ($alumno) use ($maxFaltas) {
            return $alumno->faltas == $maxFaltas;
        });

        // Seleccionar un alumno al azar entre los que tienen el número máximo de faltas
        $alumnoEnRiesgo = $alumnosMaxFaltas->random();

        // Asegurar que la propiedad faltas está presente en el alumno en riesgo
        $alumnoEnRiesgo->faltas = $alumnoEnRiesgo->faltas;
    } else {
        // Si no hay alumnos con faltas, definir como null
        $alumnoEnRiesgo = null;
    }

    // Obtener los justificantes pendientes para este profesor
    $justificantes = Justificante::where('estado', 'pendiente')
        ->whereHas('attendance.group', function ($query) use ($profesorId) {
            $query->where('profesor_id', $profesorId);
        })
        ->with(['alumno', 'attendance.group'])
        ->get();

    // Pasar los datos a la vista del dashboard del profesor
    return view('dashboard-profesor', [
        'alumnoEnRiesgo' => $alumnoEnRiesgo,
        'justificantes' => $justificantes
    ]);
}




    public function alumnoDashboard()
    {
        // Obtener el alumno autenticado
        $user = Auth::user();

        // Obtener los grupos a los que pertenece el alumno
        $grupos = $user->groups;

        // Obtener las asistencias del alumno, incluyendo el grupo y el justificante
        $asistencias = Attendance::where('user_id', $user->id)
            ->with(['group', 'justificante'])
            ->orderBy('date', 'desc')
            ->get();

        // Crear una colección para los días de clase
        $clasesPorDia = collect();

        // Obtener los días de clase del mes actual para cada grupo del alumno
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
        $daysInMonth = $currentDate->daysInMonth;

        foreach ($grupos as $grupo) {
            // Obtener los días de clase (ej: "lunes, miércoles, viernes")
            $classDays = explode(',', $grupo->class_days);
            $classDays = array_map('trim', $classDays);
            $classDays = array_map('strtolower', $classDays); // Convertir a minúsculas para comparación

            // Obtener el horario de clase
            $classScheduleParts = explode(' - ', $grupo->class_schedule); // Separar la hora de inicio y la hora de fin

            $horario = [
                'inicio' => $classScheduleParts[0] ?? 'N/A',
                'fin' => $classScheduleParts[1] ?? 'N/A'
            ];

            // Iterar sobre todos los días del mes para identificar los días de clases
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($currentYear, $currentMonth, $day);
                $dayNameSpanish = strtolower($date->locale('es')->isoFormat('dddd'));

                // Si el día actual coincide con un día de clase del grupo
                if (in_array($dayNameSpanish, $classDays)) {
                    $clasesPorDia->push([
                        'date' => $date->toDateString(),
                        'group_name' => $grupo->name,
                        'horario' => $horario
                    ]);
                }
            }
        }

        // Agrupar las clases por fecha
        $clasesPorDia = $clasesPorDia->groupBy('date');

        // Pasar los datos a la vista
        return view('dashboard-alumno', [
            'user' => $user,
            'clasesPorDia' => $clasesPorDia,
            'asistencias' => $asistencias,
        ]);
    }
    public function aceptar($id)
    {
        try {
            // Buscar el justificante por su ID
            $justificante = Justificante::findOrFail($id);
    
            // Verificar que el justificante pertenece al profesor autenticado
            $profesorId = Auth::id();
            if ($justificante->attendance->group->profesor_id != $profesorId) {
                return redirect()->back()->with('error', 'No tienes permiso para aceptar este justificante.');
            }
    
            // Resto del código...
        } catch (\Exception $e) {
            // Manejo de excepciones...
        }
    }

}
