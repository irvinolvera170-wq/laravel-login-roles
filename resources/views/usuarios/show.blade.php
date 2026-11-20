@extends('layouts.app')

@section('title', 'Detalle de usuario')

@section('content')
<div class="container min-vh-100 d-flex justify-content-center align-items-center py-4">
    <div class="card shadow-sm w-100" style="max-width: 440px;">
        <div class="card-body p-4">
            <h3 class="mb-4">Detalle de usuario</h3>

            <dl class="row mb-4">
                <dt class="col-sm-4">ID</dt>
                <dd class="col-sm-8">{{ $usuario->id }}</dd>
                <dt class="col-sm-4">Nombre</dt>
                <dd class="col-sm-8">{{ $usuario->name }}</dd>
                <dt class="col-sm-4">Correo</dt>
                <dd class="col-sm-8">{{ $usuario->email }}</dd>
                <dt class="col-sm-4">Rol</dt>
                <dd class="col-sm-8">
                    <span class="badge {{ $usuario->esAdmin() ? 'bg-danger' : 'bg-secondary' }}">{{ $usuario->role }}</span>
                </dd>
                <dt class="col-sm-4">Registrado</dt>
                <dd class="col-sm-8">{{ $usuario->created_at->format('d/m/Y H:i') }}</dd>
                <dt class="col-sm-4">Actualizado</dt>
                <dd class="col-sm-8">{{ $usuario->updated_at->format('d/m/Y H:i') }}</dd>
            </dl>

            @if (auth()->user()->esAdmin() || !$usuario->esAdmin())
                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary">Editar</a>
            @endif
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Volver</a>
        </div>
    </div>
</div>
@endsection