@extends('layouts.app')

@section('title', 'Editar Perfil del Profesor')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h2 class="text-center mb-0 text-white">Editar Perfil del Profesor</h2>
        </div>
        <div class="card-body">
            <!-- Mensajes de estado -->
            @if (session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
            @endif

            <!-- Formulario para actualizar el perfil, incluyendo la foto -->
            <form action="{{ route('profesor.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="form-group mb-4 text-center">
                    <!-- Área circular para mostrar la foto de perfil -->
                    <div class="profile-image-container mt-3">
                        <img id="profileImagePreview" 
                             src="{{ $user->photo ? url($user->photo) : asset('https://cdn-icons-png.flaticon.com/512/6073/6073873.png') }}" 
                             alt="Foto de perfil" 
                             class="img-thumbnail rounded-circle"
                             style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #ccc;">
                    </div>

                    <input type="file" name="photo" accept="image/png, image/jpeg, image/jpg" 
                           onchange="previewImage(event)" class="form-control mt-3">
                    @error('photo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="name" class="form-label font-weight-bold">Nombre</label>
                    <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="email" class="form-label font-weight-bold">Correo electrónico</label>
                    <input type="email" name="email" class="form-control form-control-lg" value="{{ $user->email }}" readonly>
                </div>

                <div class="form-group mb-4">
                    <label for="current_password" class="form-label font-weight-bold">Contraseña Actual (solo si quieres cambiar la contraseña)</label>
                    <input type="password" name="current_password" class="form-control form-control-lg" placeholder="Ingresa tu contraseña actual si deseas cambiarla">
                    @error('current_password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="new_password" class="form-label font-weight-bold">Nueva Contraseña</label>
                    <input type="password" name="new_password" class="form-control form-control-lg" placeholder="Ingresa la nueva contraseña">
                    @error('new_password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <label for="new_password_confirmation" class="form-label font-weight-bold">Confirmar Nueva Contraseña</label>
                    <input type="password" name="new_password_confirmation" class="form-control form-control-lg" placeholder="Confirma la nueva contraseña">
                    @error('new_password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-save"></i> Actualizar perfil
                    </button>
                </div>
            </form>

            <!-- Formulario para eliminar la foto de perfil -->
            @if ($user->photo)
                <form action="{{ route('profesor.profile.deletePhoto') }}" method="POST" class="d-flex justify-content-center mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-lg">
                        <i class="fas fa-trash-alt"></i> Eliminar foto de perfil
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    // Función para mostrar la vista previa de la imagen seleccionada
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profileImagePreview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
