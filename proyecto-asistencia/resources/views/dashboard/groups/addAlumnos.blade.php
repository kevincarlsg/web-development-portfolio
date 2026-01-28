@extends('layouts/app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-6">Editar Grupo: {{ $group->name }}</h1>

    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <form action="{{ route('groups.addAlumnos', $group->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="alumnos" class="block text-white font-semibold mb-2">Agregar Alumnos al Grupo</label>
                <select name="alumnos[]" id="alumnos" class="w-full px-4 py-2 rounded-lg text-gray-900" multiple>
                    @foreach($alumnos as $alumno)
                        <option value="{{ $alumno->id }}"
                            {{ $group->alumnos->contains($alumno->id) ? 'selected' : '' }}>
                            {{ $alumno->name }} ({{ $alumno->email }})
                        </option>
                    @endforeach
                </select>
                <small class="text-gray-400">Mantén presionada la tecla Ctrl para seleccionar múltiples alumnos.</small>
            </div>

            <div class="flex space-x-4 mt-4">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700">
                    Actualizar Grupo
                </button>
                <a href="{{ route('groups.index') }}" class="px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg shadow hover:bg-gray-700">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

