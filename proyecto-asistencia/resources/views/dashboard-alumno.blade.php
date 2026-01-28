@extends('layouts.appStudent')

@section('title', 'Dashboard Alumno')

@section('content')

<div class="container-fluid px-4">
    <!-- Título de la página -->
    <div class="row align-items-center mb-4">
        <div class="col-12">
            <h1 class="h3 text-primary">Bienvenido Alumno</h1>
        </div>
    </div>

    <!-- Mensajes de éxito y error -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Mostrar errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Error!</strong> Por favor corrige los siguientes errores:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Historial de Asistencias -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <h3 class="card-title mb-0 text-white">Mi Historial de Asistencias</h3>
                </div>
                <div class="card-body">
                    @if ($asistencias->isEmpty())
                        <p class="text-center">No hay registros de asistencia disponibles.</p>
                    @else
                        <table class="table table-hover table-sm table-bordered align-middle bg-white">
                            <thead class="table-white">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Grupo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($asistencias as $asistencia)
                                    <tr
                                        @if($asistencia->status == 'absent') style="background-color: #FFFFF;"
                                        @elseif($asistencia->status == 'on_time') style="background-color: #FFFFF;"
                                        @elseif($asistencia->status == 'late_a' || $asistencia->status == 'late_b') style="background-color: #FFFFFF;" @endif
                                    >
                                        <td>{{ \Carbon\Carbon::parse($asistencia->date)->format('d/m/Y') }}</td>
                                        <td>{{ $asistencia->group->name }}</td>
                                        <td>
                                            @switch($asistencia->status)
                                                @case('on_time')
                                                    <span class="text-success">A Tiempo</span>
                                                    @break
                                                @case('late_a')
                                                    <span class="text-warning">Retardo Tipo A</span>
                                                    @break
                                                @case('late_b')
                                                    <span class="text-warning">Retardo Tipo B</span>
                                                    @break
                                                @case('absent')
                                                    <span class="text-danger">Ausente</span>
                                                    @break
                                                @default
                                                    <span class="text-muted">{{ ucfirst($asistencia->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if ($asistencia->status === 'absent' && !$asistencia->justificante && \Carbon\Carbon::parse($asistencia->date)->gte(\Carbon\Carbon::now()->subDays(7)))
                                                <!-- Botón para abrir el modal de subir justificante -->
                                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#justificanteModal_{{ $asistencia->id }}">
                                                    Subir Justificante
                                                </button>

                                                <!-- Modal para subir justificante -->
                                                <div class="modal fade" id="justificanteModal_{{ $asistencia->id }}" tabindex="-1" aria-labelledby="justificanteModalLabel_{{ $asistencia->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form action="{{ route('justificantes.store') }}" method="POST" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="attendance_id" value="{{ $asistencia->id }}">
                                                                <input type="hidden" name="alumno_id" value="{{ auth()->user()->id }}">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="justificanteModalLabel_{{ $asistencia->id }}">Subir Justificante</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label for="archivo_{{ $asistencia->id }}" class="form-label">Selecciona un archivo (PDF, máximo 2MB)</label>
                                                                        <input type="file" id="archivo_{{ $asistencia->id }}" name="archivo" accept="application/pdf" class="form-control" required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                    <button type="submit" class="btn btn-outline-primary">Subir</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($asistencia->justificante)
                                                @if ($asistencia->justificante->status == 'pending')
                                                    <span class="text-warning">Justificante en revisión</span>
                                                @elseif ($asistencia->justificante->status == 'accepted')
                                                    <span class="text-success">Justificante aceptado</span>
                                                @elseif ($asistencia->justificante->status == 'rejected')
                                                    <span class="text-danger">Justificante rechazado</span>
                                                @endif
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Paginación si es necesaria -->
                        {{-- $asistencias->links() --}}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Calendario Escolar -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">Calendario de {{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM YYYY') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Generar el calendario del mes actual -->
                    @php
                        $currentDate = \Carbon\Carbon::now();
                        $firstDayOfMonth = $currentDate->copy()->startOfMonth();
                        $lastDayOfMonth = $currentDate->copy()->endOfMonth();
                        $currentDay = $firstDayOfMonth->copy();
                        $startOffset = $firstDayOfMonth->dayOfWeekIso - 1;
                    @endphp

                    <div class="calendar-container">
                        <table class="table table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Lun</th>
                                    <th>Mar</th>
                                    <th>Mié</th>
                                    <th>Jue</th>
                                    <th>Vie</th>
                                    <th>Sáb</th>
                                    <th>Dom</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $dayCounter = 1;
                                @endphp

                                <tr>
                                    @for ($i = 0; $i < $startOffset; $i++)
                                        <td></td>
                                    @endfor

                                    @while ($currentDay->month == $currentDate->month)
                                        @php
                                            $dateString = $currentDay->toDateString();
                                            $classesOnThisDay = $clasesPorDia->get($dateString, []);
                                        @endphp

                                        <td @if($classesOnThisDay) class="bg-primary text-white calendar-day" data-date="{{ $currentDay->toDateString() }}" @endif>
                                            <div class="calendar-day-content">
                                                <strong>{{ $currentDay->day }}</strong>
                                            </div>
                                        </td>

                                        @if ($currentDay->dayOfWeekIso == 7)
                                            </tr><tr>
                                        @endif

                                        @php
                                            $currentDay->addDay();
                                        @endphp
                                    @endwhile

                                    @for ($i = $currentDay->dayOfWeekIso; $i <= 7 && $i > 1; $i++)
                                        <td></td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <!-- Posición del toast -->
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3">
        <!-- Los toasts se agregarán aquí dinámicamente -->
    </div>
</div>

<!-- Scripts -->
<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Añadir evento de clic a los días coloreados del calendario
        document.querySelectorAll('.calendar-day').forEach(function (dayElement) {
            dayElement.addEventListener('click', function () {
                let date = dayElement.getAttribute('data-date');
                let classes = @json($clasesPorDia);
                if (classes.hasOwnProperty(date)) {
                    showNextClassToast(date, classes[date]);
                } else {
                    // Mostrar un toast de no hay clases
                    showNextClassToast(date, []); 
                }
            });
        });
    });

    function showNextClassToast(date, classes) {
        let currentDateTime = new Date();
        let selectedDate = new Date(date + 'T00:00:00');
        let isToday = currentDateTime.toDateString() === selectedDate.toDateString();

        let upcomingClasses = classes.filter(function(classInfo) {
            let classStartTime = new Date(date + 'T' + classInfo.horario.inicio);
            return isToday ? classStartTime > currentDateTime : true;
        });

        let toastMessage = '';

        if (upcomingClasses.length === 0) {
            // No hay clases en el día seleccionado
            toastMessage = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <span>No hay clases futuras en este día.</span>
                </div>`;
        } else {
            // Ordenar las clases por hora de inicio
            upcomingClasses.sort(function(a, b) {
                return a.horario.inicio.localeCompare(b.horario.inicio);
            });

            let nextClass = upcomingClasses[0];
            let classStartTime = new Date(date + 'T' + nextClass.horario.inicio);
            let timeDifference = classStartTime - currentDateTime;

            if (isToday) {
                if (timeDifference <= 0) {
                    toastMessage = `
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <span>La clase ya ha comenzado.</span>
                        </div>`;
                } else {
                    let minutes = Math.floor((timeDifference / (1000 * 60)) % 60);
                    let hours = Math.floor((timeDifference / (1000 * 60 * 60)) % 24);
                    let remainingTime = (hours > 0 ? hours + ' hora' + (hours > 1 ? 's' : '') + ' ' : '') + minutes + ' minuto' + (minutes !== 1 ? 's' : '');
                    toastMessage = `
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock-fill me-2"></i>
                            <span>Siguiente clase: ${nextClass.group.name}. Empieza en ${remainingTime}.</span>
                        </div>`;
                }
            } else {
                toastMessage = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-event-fill me-2"></i>
                        <span>Siguiente clase: ${nextClass.group.name}. Programada para las ${nextClass.horario.inicio}.</span>
                    </div>`;
            }
        }

        let toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-bg-primary border-0 mb-3 shadow-lg';
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.setAttribute('data-bs-autohide', 'true');
        toastEl.setAttribute('data-bs-delay', '5000');

        // Ajustar el ancho máximo y el diseño para que sea más atractivo y amplio
        // Aumentar el ancho del toast
        toastEl.style.maxWidth = '500px'; 
        // Para que ocupe el ancho disponible del contenedor
        toastEl.style.width = '100%'; 
        toastEl.style.padding = '15px';
        toastEl.style.borderRadius = '10px';

        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    ${toastMessage}
                </div>
                <button type="button" class="btn-close btn-close-white ms-3 me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
            </div>
        `;

        let toastContainer = document.getElementById('toastContainer');
        toastContainer.appendChild(toastEl);

        let toast = new bootstrap.Toast(toastEl);
        toast.show();

        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }
</script>
@endsection


