

@extends('layouts/app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-white mb-6">Pase de Lista - {{ $group->name }}</h1>

    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-semibold text-white mb-4">Escanea el código QR para registrar asistencia</h2>
        
        <div class="flex justify-center mb-4">
            {!! $qrCode !!}
        </div>

        <p class="text-gray-300"></p>
        <a href="{{ route('groups.show', $group->id) }}" class="mt-4 inline-block px-4 py-2 bg-gray-600 text-white font-semibold rounded-lg shadow hover:bg-gray-700">
            Volver a la Información del Grupo
        </a>
    </div>
</div>
@endsection

