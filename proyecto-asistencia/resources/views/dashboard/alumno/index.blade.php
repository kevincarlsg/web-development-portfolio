

@extends('layouts/appStudent')

@section('content')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-8">
            <h1 class="h3 text-primary">Mis Grupos</h1>
        </div>

    </div>

    @if ($groups->isEmpty())
        <div class="alert alert-info text-center" role="alert">
            No estás en ningún grupo actualmente.
        </div>
    @else
        <div class="row">
            @foreach ($groups as $group)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0 text-white">{{ $group->name }}</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Escuela:</strong> {{ $group->school ? $group->school->name : 'No asignada' }}</p>
                            <p><strong>Profesor:</strong> {{ $group->profesor ? $group->profesor->name : 'Sin profesor' }}</p>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('alumno.show', $group->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
