
@extends('layouts/app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-primary">Pase de Lista Manual - {{ $group->name }} - {{ \Carbon\Carbon::today()->format('d/m/Y') }}</h1>

    <!-- Tabla de Alumnos para Pase de Lista -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="card-title mb-0 text-white">Registrar Asistencia</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('groups.storeManualAttendance', $group->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <table class="table table-bordered table-hover align-middle">
                        <tbody>
                            @foreach($alumnos as $alumno)
                                @php
                                    // Obtener la asistencia actual del alumno para la fecha de hoy
                                    $asistencia = $attendances->where('user_id', $alumno->id)->where('date', \Carbon\Carbon::today()->toDateString())->first();
                                    $status = $asistencia ? $asistencia->status : 'absent'; // Si no hay asistencia registrada, se asigna "Falta" por defecto
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $alumno->name }}</td>
                                    <td class="text-center">
                                        <select name="attendance[{{ $alumno->id }}]" class="form-select w-auto mx-auto">
                                            <option value="on_time" {{ $status == 'on_time' ? 'selected' : '' }}>A Tiempo</option>
                                            @if ($group->tolerance)
                                                <option value="late_a" {{ $status == 'late_a' ? 'selected' : '' }}>Retardo A</option>
                                                <option value="late_b" {{ $status == 'late_b' ? 'selected' : '' }}>Retardo B</option>
                                            @endif
                                            <option value="absent" {{ $status == 'absent' ? 'selected' : '' }}>Falta</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-outline-primary btn-lg">
                        Guardar Asistencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
