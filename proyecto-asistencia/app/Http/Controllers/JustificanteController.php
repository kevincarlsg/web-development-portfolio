<?php


/**
* Controlador para los procesos del justificante.
* HASH
* Autor: Gadiel Palma Ramos
* Fecha de creación: 27/10/2024
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Justificante;
use App\Models\Attendance;
use Illuminate\Support\Facades\Log;

class JustificanteController extends Controller
{
    /**
     * Almacena un justificante subido por un alumno.
     */
    public function store(Request $request)
    {
        // Validación del justificante
        $validatedData = $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'alumno_id' => 'required|exists:users,id',
            'archivo' => 'required|mimes:pdf|max:2048', // Solo archivos PDF con un tamaño máximo de 2MB
        ]);

        try {
            // Guardar el archivo
            $filePath = $request->file('archivo')->store('justificantes', 'public');

            // Guardar el justificante en la base de datos
            Justificante::create([
                'attendance_id' => $validatedData['attendance_id'],
                'alumno_id' => $validatedData['alumno_id'],
                'fecha' => now(),
                'archivo' => $filePath,
                'estado' => 'pendiente',
            ]);

            return redirect()->back()->with('success', 'Justificante enviado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al enviar justificante: " . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al enviar el justificante. Por favor, intenta de nuevo.');
        }
    }

    /**
     * Acepta un justificante y actualiza la asistencia relacionada.
     */
    public function aceptar($id)
    {
        try {
            // Buscar el justificante por su ID
            $justificante = Justificante::findOrFail($id);

            // Actualizar el estado del justificante a 'aceptado'
            $justificante->estado = 'aceptado';
            $justificante->save();

            // Buscar la asistencia relacionada y actualizar su estado
            $attendance = $justificante->attendance; // Usando la relación para hacer el código más limpio
            if ($attendance) {
                $attendance->status = 'on_time'; // Cambiar el estado a 'on_time' si el justificante fue aceptado
                $attendance->save();
            }

            return redirect()->back()->with('success', 'Justificante aceptado y asistencia actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al aceptar justificante: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al aceptar el justificante.');
        }
    }

    /**
     * Rechaza un justificante y mantiene la asistencia como 'absent'.
     */
    public function rechazar($id)
    {
        try {
            // Buscar el justificante por su ID
            $justificante = Justificante::findOrFail($id);

            // Actualizar el estado del justificante a 'rechazado'
            $justificante->estado = 'rechazado';
            $justificante->save();

            // Mantener la asistencia en 'absent', ya que el justificante fue rechazado
            $attendance = $justificante->attendance; // Usando la relación para hacer el código más limpio
            if ($attendance) {
                $attendance->status = 'absent'; // Mantener el estado como 'absent'
                $attendance->save();
            }

            return redirect()->back()->with('success', 'Justificante rechazado correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al rechazar justificante: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al rechazar el justificante.');
        }
    }
}
