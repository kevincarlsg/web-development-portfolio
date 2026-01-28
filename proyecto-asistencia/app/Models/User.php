<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_PROFESOR = 'profesor';
    const ROLE_ALUMNO = 'alumno';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function isProfesor()
    {
        return $this->role === self::ROLE_PROFESOR;
    }

    public function isAlumno()
    {
        return $this->role === self::ROLE_ALUMNO;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
