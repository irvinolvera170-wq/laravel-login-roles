@extends('layouts.app')

@section('title', 'Crear usuario')

@section('content')
<div class="container min-vh-100 d-flex justify-content-center align-items-center py-4">
    <div class="card shadow-sm w-100" style="max-width: 440px;">
        <div class="card-body p-4">
            <h3 class="mb-4">Crear usuario</h3>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('usuarios.store') }}" novalidate>
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
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="form-control @error('password_confirmation') is-invalid @enderror">
                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror">
                        <option value="empleado" {{ old('role') == 'empleado' ? 'selected' : '' }}>Empleado</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100">Crear usuario</button>
            </form>

            <a href="{{ route('home') }}" class="d-block text-center mt-3">← Volver al Home</a>
        </div>
    </div>
</div>
@endsection