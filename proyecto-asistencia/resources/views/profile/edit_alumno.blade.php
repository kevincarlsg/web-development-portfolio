@extends('layouts.appStudent')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h2 class="text-center mb-0 text-white">Editar Perfil</h2>
        </div>
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success text-center">{{ session('status') }}</div>
            @endif

            <!-- Formulario para actualizar el perfil -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Foto de perfil -->
                <div class="form-group mb-4 text-center">
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
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo para editar el nombre -->
                <div class="form-group mb-4">
                    <label for="name" class="form-label font-weight-bold">Nombre</label>
                    <input type="text" name="name" class="form-control form-control-lg" 
                           value="{{ old('name', $user->name) }}" placeholder="Ingresa tu nombre">
                    @error('name')
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Correo electrónico (solo lectura) -->
                <div class="form-group mb-4">
                    <label for="email" class="form-label font-weight-bold">Correo electrónico</label>
                    <input type="email" name="email" class="form-control form-control-lg" 
                           value="{{ $user->email }}" readonly>
                </div>

                <!-- Campo para contraseña actual -->
                <div class="form-group mb-4">
                    <label for="current_password" class="form-label font-weight-bold">Contraseña Actual</label>
                    <input type="password" name="current_password" class="form-control form-control-lg" 
                           placeholder="Ingresa tu contraseña actual si deseas cambiarla">
                    @error('current_password')
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Campo para nueva contraseña -->
                <div class="form-group mb-4">
                    <label for="new_password" class="form-label font-weight-bold">Nueva Contraseña</label>
                    <input type="password" name="new_password" class="form-control form-control-lg" 
                           placeholder="Ingresa la nueva contraseña">
                    @error('new_password')
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirmar nueva contraseña -->
                <div class="form-group mb-4">
                    <label for="new_password_confirmation" class="form-label font-weight-bold">Confirmar Nueva Contraseña</label>
                    <input type="password" name="new_password_confirmation" class="form-control form-control-lg" 
                           placeholder="Confirma la nueva contraseña">
                    @error('new_password_confirmation')
                        <span class="text-danger d-block mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botón de enviar -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Actualizar perfil
                    </button>
                </div>
            </form>
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
