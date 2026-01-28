
@extends('layouts.app')

@section('title', 'Información del Alumno')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-primary">Información del Alumno</h1>
    <h1 class="mt-4 text-primary"></h1>
    <div class="card mb-4 shadow border-1">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 class="text-dark mb-3">{{ $alumno->name }}</h2>
                    <p class="text-muted"><strong>Email:</strong> {{ $alumno->email }}</p>
                    <p class="text-muted"><strong>Grupo:</strong> {{ $group->name }}</p>
                </div>
                <div class="col-md-6 text-center">
                    <h4 class="text-dark mb-4">Resumen de Asistencias</h4>
                    <canvas id="attendanceChart" width="400" height="200" class="shadow-lg rounded"></canvas>
                </div>
            </div>
        </div>
    </div>
    <h1 class="mt-4 text-primary">Detalle de Asistencias</h3>
    <h1 class="mt-4 text-primary"></h3>
        <div class="card mb-4 shadow border-1">
            <div class="card-body">
                <!-- Tabla de Detalle de Asistencias -->
                <div class="table-responsive mb-4">
                    <table class="table table-hover table-striped table-bordered shadow-lg rounded">
                        <thead class="">
                            <tr>
                                <th scope="col" class="text-dark">Fecha</th>
                                <th scope="col" class="text-darkz">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d/m/Y') }}</td>
                                    <td>
                                        @switch($attendance->status)
                                            @case('on_time')
                                                <span class="text-success">Asistencia</span>
                                                @break
                                            @case('late_a')
                                                <span class="text-warning text-dark">Retardo A</span>
                                                @break
                                            @case('late_b')
                                                <span class="text-warning">Retardo B</span>
                                                @break
                                            @case('absent')
                                                <span class="text-danger">Falta</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
            <a href="{{ route('groups.show', $group->id) }}" class="btn btn-outline-danger mt-4">
                <i class="fas fa-arrow-left"></i> Volver al Grupo
            </a>


<!-- Incluir Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Asistencias', 'Faltas', 'Retardos A', 'Retardos B'],
            datasets: [{
                data: [{{ $attendanceCount }}, {{ $absentCount }}, {{ $lateACount }}, {{ $lateBCount }}],
                backgroundColor: ['#4BC07A', '#FF6961', '#FFC107', '#FF9800'],
                hoverOffset: 4,
                borderColor: 'rgba(255, 255, 255, 0.2)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'black',
                        font: {
                            size: 14
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
