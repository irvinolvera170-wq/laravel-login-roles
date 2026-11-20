@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="container min-vh-100 d-flex justify-content-center align-items-center py-4">
    <div class="card shadow-sm w-100" style="max-width: 420px;">
        <div class="card-body p-4">
            <h3 class="text-center mb-4">Crear cuenta</h3>

            <form method="POST" action="{{ route('registro.store') }}" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <div class="form-text">Mínimo 8 caracteres.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="form-control @error('password_confirmation') is-invalid @enderror">
                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-success w-100">Registrarme</button>
            </form>

            <p class="text-center mt-3 mb-0">
                <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
            </p>
        </div>
    </div>
</div>
@endsection