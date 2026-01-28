
@extends('layouts/app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-primary">Agregar Alumnos al Grupo: {{ $group->name }}</h1>

    @if (session('success'))
        <div class="alert alert-success text-center mt-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Buscar Alumnos -->
    <form method="GET" action="{{ route('groups.addAlumnosForm', $group->id) }}" class="input-group mb-4 mt-4">
        <input type="text" name="search" class="form-control" placeholder="Buscar por correo electrónico" value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-primary">
            Buscar
        </button>
    </form>

    <!-- Importar Alumnos desde Archivo Excel -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="card-title text-white mb-0">Cargar Archivo Excel</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('groups.importAlumnosPhpSpreadsheet', $group->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label text-muted">Cargar Archivo Excel</label>
                    <input type="file" name="file" id="file" class="form-control" required>
                    <small class="form-text text-muted">Asegúrate de que el archivo sea un formato válido (.xlsx, .xls).</small>
                </div>
                <button type="submit" class="btn btn-outline-primary">
                    Importar Alumnos
                </button>
            </form>
        </div>
    </div>

    <!-- Agregar Alumnos al Grupo -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h2 class="card-title mb-0 text-white">Selecciona Alumnos para Agregar</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('groups.addAlumnos', $group->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="alumnos" class="form-label text-muted">Selecciona Alumnos para Agregar</label>
                    <select name="alumnos[]" id="alumnos" class="form-select" multiple required>
                        @foreach ($alumnos as $alumno)
                            <option value="{{ $alumno->id }}">{{ $alumno->name }} ({{ $alumno->email }})</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">Mantén presionada la tecla Ctrl (Cmd en Mac) para seleccionar múltiples alumnos.</small>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-outline-primary">
                        Agregar Alumnos
                    </button>
                    <a href="{{ route('groups.show', $group->id) }}" class="btn btn-outline-danger">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
