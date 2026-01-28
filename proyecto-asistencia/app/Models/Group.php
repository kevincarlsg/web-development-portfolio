<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\School;
use App\Models\Subject;
use App\Models\Attendance;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'profesor_id',
        'school_id',
        'subject_id',
        'class_days',
        'class_schedule',
        'school_period',
        'tolerance',
        'qr_interval', // Agregado para permitir la asignación masiva
    ];

    /**
     * Relación con la Escuela asociada.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Relación con la Materia asociada.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relación con el Profesor que creó el grupo.
     */
    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }
    

    /**
     * Relación muchos a muchos con Alumnos.
     */
    public function alumnos()
    {
        return $this->belongsToMany(User::class, 'group_user', 'group_id', 'user_id');
    }

    /**
     * Relación uno a muchos con Asistencias.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
