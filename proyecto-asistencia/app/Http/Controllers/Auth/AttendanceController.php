<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Group;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Constructor para la protección de autenticación
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Método para obtener el resumen de asistencias para una fecha y grupo determinados.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $date
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendanceSummary(Request $request, $date)
    {
        // Obtener el ID del grupo desde los parámetros de la solicitud
        $groupId = $request->input('group_id');

        // Validar que el grupo exista
        $group = Group::find($groupId);
        if (!$group) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        // Filtrar las asistencias del grupo en la fecha seleccionada
        $attendances = Attendance::where('group_id', $groupId)
            ->where('date', $date)
            ->get();

        // Resumir los datos para la gráfica
        $summary = [
            'totalFaltas' => $attendances->where('status', 'absent')->count(),
            'totalAsistencias' => $attendances->where('status', 'on_time')->count(),
            'totalRetardosA' => $attendances->where('status', 'late_a')->count(),
            'totalRetardosB' => $attendances->where('status', 'late_b')->count(),
        ];

        // Crear la respuesta JSON
        return response()->json($summary);
    }

    /**
     * Generar el código QR para el pase de lista.
     *
     * @param int $groupId
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateQr($groupId)
    {
        // Validar que el grupo exista
        $group = Group::find($groupId);
        if (!$group) {
            return response()->json(['error' => 'Grupo no encontrado'], 404);
        }

        // Configurar la validez del QR a 30 minutos
        $expiration = Carbon::now()->addMinutes(30);
        $qrUrl = route('groups.attendance', [
            'group' => $group->id,
            'expires_at' => $expiration->timestamp,
        ]);

        // Generar el código QR (utilizando una librería de QR Code como SimpleSoftwareIO\QrCode)
        $qrCode = base64_encode(
            \QrCode::format('svg')->size(300)->generate($qrUrl)
        );

        return response()->json([
            'qrCode' => $qrCode,
            'expiration' => $expiration->timestamp,
        ]);
    }

    /**
     * Mostrar la página del grupo con la información de asistencia.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        // Buscar el grupo y cargar relaciones
        $group = Group::with(['school', 'subject', 'alumnos'])->findOrFail($id);

        // Verificar que el usuario tenga permiso para ver el grupo
        if (auth()->user()->role != 'profesor' && !$group->alumnos->contains(auth()->user())) {
            return redirect()->route('groups.index');
        }

        // Obtener las fechas únicas de asistencia para el grupo
        $dates = Attendance::where('group_id', $group->id)
            ->select('date')
            ->distinct()
            ->orderBy('date', 'asc')
            ->pluck('date');

        // Obtener resumen de asistencia si se seleccionó una fecha
        $selectedDate = request('selected_date');
        $attendanceSummary = ['totalFaltas' => 0, 'totalAsistencias' => 0, 'totalRetardosA' => 0, 'totalRetardosB' => 0];
        if ($selectedDate) {
            $attendances = Attendance::where('group_id', $group->id)
                ->where('date', $selectedDate)
                ->get();

            $attendanceSummary = [
                'totalFaltas' => $attendances->where('status', 'absent')->count(),
                'totalAsistencias' => $attendances->where('status', 'on_time')->count(),
                'totalRetardosA' => $attendances->where('status', 'late_a')->count(),
                'totalRetardosB' => $attendances->where('status', 'late_b')->count(),
            ];
        }

        // Pasar la información a la vista
        return view('dashboard.groups.show', compact('group', 'dates', 'attendanceSummary', 'selectedDate'));
    }
}