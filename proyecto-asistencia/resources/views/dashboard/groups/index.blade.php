@extends('layouts.app')

@section('title', 'Lista de Grupos')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4 shadow-lg border-0">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h3 class="mb-0 text-center text-white">Lista de Grupos</h3>
            @if(Auth::user()->isProfesor())
                <!-- Solo los profesores pueden crear un nuevo grupo -->
                <a href="{{ route('groups.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-plus"></i> Crear Nuevo Grupo
                </a>
            @endif
        </div>
        <div class="card-body">
            @if($groups->isEmpty())
                <div class="alert text-center">
                    <strong>No hay grupos disponibles.</strong>
                </div>
            @else
                <div class="card-body">
    @if($groups->isEmpty())
        <div class="alert text-center">
            <strong>No hay grupos disponibles.</strong>
        </div>
    @else
        <div class="list-group">
            @foreach($groups as $group)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center mb-3 p-3 shadow-sm">
                    <span class="h5 mb-0 text-dark">
                        <i class="fas fa-users me-2"></i>{{ $group->name }}
                    </span>
                    <div class="btn-group">
                        <!-- Botón Ver Grupo -->
                        <a href="{{ route('groups.show', $group->id) }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="fas fa-eye"></i> Ver Grupo
                        </a>

                        @if(Auth::user()->isProfesor())
                            <!-- Botón Editar -->
                            <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            <!-- Botón Eliminar con Modal de Confirmación -->
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-{{ $group->id }}">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </button>
                        @endif
                    </div>

                    <!-- Modal de Confirmación para Eliminar -->
                    @if(Auth::user()->isProfesor())
                    <div class="modal fade" id="confirmDeleteModal-{{ $group->id }}" tabindex="-1" aria-labelledby="confirmDeleteModalLabel-{{ $group->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p class="mb-0">¿Estás seguro de que deseas eliminar el grupo <strong>{{ $group->name }}</strong>? Esta acción no se puede deshacer.</p>
                                </div>
                                <div class="modal-footer py-2">
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            @endforeach
        </div>
    @endif
</div>

            @endif
        </div>
    </div>
</div>
@endsection
