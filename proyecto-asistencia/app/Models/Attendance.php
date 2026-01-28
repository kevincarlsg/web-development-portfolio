<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'date',
        'status',
        'retardos_tipo_a',
        'retardos_tipo_b',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con justificante
    public function justificante()
    {
        return $this->hasOne(Justificante::class);
    }

    // Método para aplicar la lógica de retardo y falta
    public function applyLateRules($minutesLate)
    {
        if ($minutesLate <= 10) {
            $this->status = 'on_time';
        } elseif ($minutesLate <= 20) {
            $this->status = 'late_a';
            $this->retardos_tipo_a++;
            if ($this->retardos_tipo_a >= 3) {
                $this->status = 'absent';
                $this->retardos_tipo_a = 0; // Reinicia el contador de retardos tipo A
            }
        } elseif ($minutesLate <= 30) {
            $this->status = 'late_b';
            $this->retardos_tipo_b++;
            if ($this->retardos_tipo_b >= 2) {
                $this->status = 'absent';
                $this->retardos_tipo_b = 0; // Reinicia el contador de retardos tipo B
            }
        } else {
            $this->status = 'absent';
        }
    }

    // Método para aceptar justificante
    public function aceptarJustificante()
    {
        if ($this->justificante && $this->justificante->estado === 'pendiente') {
            $this->status = 'justified'; // Cambia el estado de la asistencia a justificado
            $this->justificante->estado = 'aceptado'; // Cambia el estado del justificante a aceptado
            $this->justificante->save();
            $this->save(); // Guarda los cambios en la base de datos
        }
    }

    // Método para rechazar justificante
    public function rechazarJustificante()
    {
        if ($this->justificante && $this->justificante->estado === 'pendiente') {
            $this->status = 'absent'; // Mantiene el estado como falta
            $this->justificante->estado = 'rechazado'; // Cambia el estado del justificante a rechazado
            $this->justificante->save();
            $this->save(); // Guarda los cambios en la base de datos
        }
    }
}
