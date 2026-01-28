@extends('layouts.app')

@section('title', 'Editar Grupo')

@section('content')
<div class="container-fluid px-4">
    <!-- Tarjeta para Editar Grupo -->
    <div class="card mb-4 shadow border-0">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0 text-center text-white">Editar Grupo</h3>
        </div>
        <div class="card-body">
            <!-- Mostrar Mensajes de Sesión -->
            @if(session('success'))
                <div class="alert alert-success" id="message-label">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" id="message-label">{{ session('error') }}</div>
            @endif

            <!-- Mostrar Errores de Validación -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulario para Editar el Grupo -->
            <form action="{{ route('groups.update', $group->id) }}" method="POST" id="group-form">
                @csrf
                @method('PUT')

                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <!-- Nombre del Grupo -->
                            <tr>
                                <td class="align-middle text-secondary fw-bold">Nombre del Grupo</td>
                                <td>
                                    <input type="text" id="name" name="name" value="{{ old('name', $group->name) }}" class="form-control" placeholder="Ingrese el nombre del grupo" required>
                                    @error('name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>

                            <!-- Selección de Escuela -->
                            <tr>
                                <td class="align-middle text-secondary fw-bold">Escuela</td>
                                <td>
                                    <select id="school_id" name="school_id" class="form-select">
                                        <option value="">Selecciona una Escuela</option>
                                        @foreach ($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id', $group->school_id) == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <a href="#" id="add-school-link" class="text-primary mt-2 d-block">Agregar Nueva Escuela</a>
                                    @error('school_id')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>

                            <!-- Campo para agregar una nueva escuela -->
                            <tr id="new-school-field" style="display: {{ old('new_school_name') ? 'table-row' : 'none' }};">
                                <td class="align-middle text-secondary fw-bold">Nombre de la Nueva Escuela</td>
                                <td>
                                    <input type="text" id="new_school_name" name="new_school_name" value="{{ old('new_school_name') }}" class="form-control" placeholder="Ingrese el nombre de la nueva escuela">
                                    @error('new_school_name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>

                            <!-- Selección de Materia -->
                            <tr>
                                <td class="align-middle text-secondary fw-bold">Materia</td>
                                <td>
                                    <select id="subject_id" name="subject_id" class="form-select">
                                        <option value="">Selecciona una Materia</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id', $group->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <a href="#" id="add-subject-link" class="text-primary mt-2 d-block">Agregar Nueva Materia</a>
                                    @error('subject_id')
                                        <div class="text-danger mt-2">Debe seleccionar una materia válida.</div>
                                    @enderror
                                </td>
                            </tr>

                            <!-- Campo para agregar una nueva materia -->
                            <tr id="new-subject-field" style="display: {{ old('new_subject_name') ? 'table-row' : 'none' }};">
                                <td class="align-middle text-secondary fw-bold">Nombre de la Nueva Materia</td>
                                <td>
                                    <input type="text" id="new_subject_name" name="new_subject_name" value="{{ old('new_subject_name') }}" class="form-control" placeholder="Ingrese el nombre de la nueva materia">
                                    @error('new_subject_name')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>

                            <!-- Días de Clases -->
                            <tr>
                                <td class="align-middle text-secondary fw-bold">Días de Clases</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                            $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                            $selectedDays = old('class_days', explode(',', $group->class_days));
                                        @endphp
                                        @foreach ($days as $day)
                                            <div class="form-check form-check-inline">
                                                <input type="checkbox" id="day_{{ strtolower($day) }}" name="class_days[]" value="{{ $day }}" class="form-check-input" {{ in_array($day, $selectedDays) ? 'checked' : '' }}>
                                                <label for="day_{{ strtolower($day) }}" class="form-check-label">{{ $day }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Campo oculto para almacenar los días seleccionados -->
                                    <input type="hidden" id="class_days" name="class_days" value="{{ old('class_days', $group->class_days) }}">
                                    <div id="days-error" class="text-danger mt-2" style="display: none;">Debe seleccionar al menos un día de clase.</div>
                                    @error('class_days')
                                        <div class="text-danger mt-2">Debe seleccionar al menos un día de clase.</div>
                                    @enderror
                                </td>
                            </tr>

                            <!-- Horario de Clases -->
                            <tr>
                                <td class="align-middle text-secondary fw-bold">Horario de Clases</td>
                                <td>
                                    <div class="d-flex">
                                        <select id="start_time" name="start_time" class="form-select me-2" required>
                                            <option value="">Hora de Inicio</option>
                                            @for ($i = 7; $i <= 20; $i++)
                                                <option value="{{ sprintf('%02d', $i) }}:00" {{ old('start_time', explode(' - ', $group->class_schedule)[0]) == sprintf('%02d', $i) . ':00' ? 'selected' : '' }}>
                                                    {{ sprintf('%02d', $i) }}:00
                                                </option>
                                                <option value="{{ sprintf('%02d', $i) }}:30" {{ old('start_time', explode(' - ', $group->class_schedule)[0]) == sprintf('%02d', $i) . ':30' ? 'selected' : '' }}>
                                                    {{ sprintf('%02d', $i) }}:30
                                                </option>
                                            @endfor
                                        </select>
                                        <select id="end_time" name="end_time" class="form-select" required>
                                            <option value="">Hora de Fin</option>
                                            @for ($i = 8; $i <= 21; $i++)
                                                <option value="{{ sprintf('%02d', $i) }}:00" {{ old('end_time', explode(' - ', $group->class_schedule)[1]) == sprintf('%02d', $i) . ':00' ? 'selected' : '' }}>
                                                    {{ sprintf('%02d', $i) }}:00
                                                </option>
                                                <option value="{{ sprintf('%02d', $i) }}:30" {{ old('end_time', explode(' - ', $group->class_schedule)[1]) == sprintf('%02d', $i) . ':30' ? 'selected' : '' }}>
                                                    {{ sprintf('%02d', $i) }}:30
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div id="time-error" class="text-danger mt-2" style="display: none;">La hora de inicio debe ser menor que la hora de fin.</div>
                                    @error('start_time')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @error('end_time')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>

                            <!-- Periodo Escolar -->
                            <tr>
                                <td class="align-middle text-secondary fw-bold">Periodo Escolar</td>
                                <td>
                                    <select id="school_period" name="school_period" class="form-select" required>
                                        <option value="">Selecciona un Periodo Escolar</option>
                                        <option value="Enero - Junio 2024" {{ old('school_period', $group->school_period) == 'Enero - Junio 2024' ? 'selected' : '' }}>Enero - Junio 2024</option>
                                        <option value="Julio - Diciembre 2024" {{ old('school_period', $group->school_period) == 'Julio - Diciembre 2024' ? 'selected' : '' }}>Julio - Diciembre 2024</option>
                                        <option value="Enero - Junio 2025" {{ old('school_period', $group->school_period) == 'Enero - Junio 2025' ? 'selected' : '' }}>Enero - Junio 2025</option>
                                    </select>
                                    @error('school_period')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>

                            <!-- Opción de Tolerancia -->
                            <tr>
                                <td class="align-middle text-secondary fw-bold">Permitir Tolerancia</td>
                                <td>
                                    <div class="form-check">
                                        <input type="checkbox" id="tolerance" name="tolerance" value="1" class="form-check-input" {{ old('tolerance', $group->tolerance) ? 'checked' : '' }}>
                                        <label for="tolerance" class="form-check-label fw-bold">Tolerancia</label>
                                    </div>
                                </td>
                            </tr>

                            <!-- Intervalo de QR (en minutos) -->
                            <tr id="qr-interval-row">
                                <td class="align-middle text-secondary fw-bold">Intervalo de QR (en minutos)</td>
                                <td>
                                    <select id="qr_interval" name="qr_interval_select" class="form-select" {{ old('tolerance', $group->tolerance) ? 'disabled' : '' }}>
                                        <option value="">Selecciona un Intervalo</option>
                                        <option value="5" {{ old('qr_interval', $group->qr_interval) == '5' ? 'selected' : '' }}>5 minutos</option>
                                        <option value="10" {{ old('qr_interval', $group->qr_interval) == '10' ? 'selected' : '' }}>10 minutos</option>
                                        <option value="15" {{ old('qr_interval', $group->qr_interval) == '15' ? 'selected' : '' }}>15 minutos</option>
                                        <option value="20" {{ old('qr_interval', $group->qr_interval) == '20' ? 'selected' : '' }}>20 minutos</option>
                                        <option value="25" {{ old('qr_interval', $group->qr_interval) == '25' ? 'selected' : '' }}>25 minutos</option>
                                    </select>
                                    <input type="hidden" id="qr_interval_hidden" name="qr_interval" value="{{ old('tolerance', $group->tolerance) ? '30' : old('qr_interval', $group->qr_interval) }}">
                                    @error('qr_interval')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Botones de Acción -->
                <div class="d-flex justify-content-center mt-4">
                    <button type="submit" class="btn btn-outline-primary me-3">
                        <i class="fas fa-save"></i> Actualizar Grupo
                    </button>
                    <a href="{{ route('groups.index') }}" class="btn btn-outline-danger">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Mostrar/ocultar campo para nueva escuela
        const addSchoolLink = document.getElementById('add-school-link');
        const newSchoolField = document.getElementById('new-school-field');
        const schoolSelect = document.getElementById('school_id');
        const newSchoolInput = document.getElementById('new_school_name');

        addSchoolLink.addEventListener('click', function (e) {
            e.preventDefault();
            if (newSchoolField.style.display === 'none' || newSchoolField.style.display === '') {
                newSchoolField.style.display = 'table-row';
                schoolSelect.disabled = true;
                schoolSelect.value = '';
                newSchoolInput.disabled = false;
            } else {
                newSchoolField.style.display = 'none';
                schoolSelect.disabled = false;
                newSchoolInput.value = '';
                newSchoolInput.disabled = true;
            }
        });

        // Mostrar/ocultar campo para nueva materia
        const addSubjectLink = document.getElementById('add-subject-link');
        const newSubjectField = document.getElementById('new-subject-field');
        const subjectSelect = document.getElementById('subject_id');
        const newSubjectInput = document.getElementById('new_subject_name');

        addSubjectLink.addEventListener('click', function (e) {
            e.preventDefault();
            if (newSubjectField.style.display === 'none' || newSubjectField.style.display === '') {
                newSubjectField.style.display = 'table-row';
                subjectSelect.disabled = true;
                subjectSelect.value = '';
                newSubjectInput.disabled = false;
            } else {
                newSubjectField.style.display = 'none';
                subjectSelect.disabled = false;
                newSubjectInput.value = '';
                newSubjectInput.disabled = true;
            }
        });

        // Validación de días seleccionados en tiempo real
        const checkboxes = document.querySelectorAll('input[name="class_days[]"]');
        const daysError = document.getElementById('days-error');

        function validateDays() {
            const checkedDays = document.querySelectorAll('input[name="class_days[]"]:checked');
            if (checkedDays.length === 0) {
                daysError.style.display = 'block';
                return false;
            } else {
                daysError.style.display = 'none';
                return true;
            }
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', validateDays);
        });

        // Mostrar/ocultar intervalo de QR dependiendo del checkbox de Tolerancia
        const toleranceCheckbox = document.getElementById('tolerance');
        const qrIntervalRow = document.getElementById('qr-interval-row');
        const qrIntervalSelect = document.getElementById('qr_interval');
        const qrIntervalHidden = document.getElementById('qr_interval_hidden');

        function toggleQrInterval() {
            if (toleranceCheckbox.checked) {
                qrIntervalRow.style.display = 'none';
                qrIntervalHidden.value = '30'; // Establecer el valor a 30 cuando se habilita la tolerancia
            } else {
                qrIntervalRow.style.display = 'table-row';
                qrIntervalHidden.value = qrIntervalSelect.value; // Restaurar el valor según la selección actual
            }
        }

        toleranceCheckbox.addEventListener('change', toggleQrInterval);

        // Inicializar visibilidad del intervalo de QR basado en el estado inicial del checkbox
        toggleQrInterval();

        // Validación de horario de inicio y fin en tiempo real
        const startTimeField = document.getElementById('start_time');
        const endTimeField = document.getElementById('end_time');
        const timeError = document.getElementById('time-error');

        function validateTimes() {
            const startTime = startTimeField.value;
            const endTime = endTimeField.value;

            if (startTime && endTime && startTime >= endTime) {
                timeError.style.display = 'block';
                return false;
            } else {
                timeError.style.display = 'none';
                return true;
            }
        }

        startTimeField.addEventListener('change', validateTimes);
        endTimeField.addEventListener('change', validateTimes);

        // Concatenar valores de los checkboxes y horarios al enviar el formulario
        document.getElementById('group-form').addEventListener('submit', function (e) {
            if (!validateDays()) {
                e.preventDefault();
                document.getElementById('message-label').innerHTML = 'Debe seleccionar al menos un día de clase.';
                document.getElementById('message-label').classList.add('alert', 'alert-danger');
                return;
            }

            if (!validateTimes()) {
                e.preventDefault();
                document.getElementById('message-label').innerHTML = 'La hora de inicio debe ser menor que la hora de fin.';
                document.getElementById('message-label').classList.add('alert', 'alert-danger');
                return;
            }

            // Asignar al campo oculto 'class_days'
            const checkedDays = document.querySelectorAll('input[name="class_days[]"]:checked');
            let daysString = '';
            checkedDays.forEach((checkbox) => {
                daysString += checkbox.value + ',';
            });
            daysString = daysString.slice(0, -1);
            document.getElementById('class_days').value = daysString;

            // Concatenar start_time y end_time para formar class_schedule
            const startTime = startTimeField.value;
            const endTime = endTimeField.value;

            if (startTime && endTime) {
                const classSchedule = `${startTime} - ${endTime}`;
                const hiddenScheduleField = document.createElement('input');
                hiddenScheduleField.type = 'hidden';
                hiddenScheduleField.name = 'class_schedule';
                hiddenScheduleField.value = classSchedule;
                document.getElementById('group-form').appendChild(hiddenScheduleField);
            }

            // Gestionar el valor de 'qr_interval'
            if (toleranceCheckbox.checked) {
                qrIntervalHidden.value = '30';
            } else {
                const qrIntervalSelectValue = qrIntervalSelect.value;
                qrIntervalHidden.value = qrIntervalSelectValue;
            }

            // Deshabilitar los checkboxes para evitar múltiples selecciones mientras se envía el formulario
            checkboxes.forEach(checkbox => {
                checkbox.disabled = true;
            });
        });
    });
</script>
@endsection
