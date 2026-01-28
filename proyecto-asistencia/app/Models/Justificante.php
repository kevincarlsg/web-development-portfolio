<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justificante extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'alumno_id',
        'fecha',
        'archivo',
        'estado',
    ];

    /**
     * Relación con el modelo User (Alumno).
     */
    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    /**
     * Relación con el modelo Attendance.
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
}
