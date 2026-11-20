@extends('layouts.app')

@section('title', 'Home')

@section('content')
<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand mb-0 h1">Mi Proyecto</span>
        <form method="POST" action="{{ route('logout') }}" class="d-flex">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
        </form>
    </div>
</nav>

<div class="container py-5">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h2>Bienvenido, {{ auth()->user()->name }}</h2>
            <span class="badge {{ auth()->user()->esAdmin() ? 'bg-danger' : 'bg-secondary' }}">
                {{ auth()->user()->role }}
            </span>
        </div>
    </div>

    @if (auth()->user()->esAdmin())
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>Crear usuario</h5>
                        <p class="text-muted">Registra un nuevo empleado o administrador.</p>
                        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">+ Crear usuario</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h5>Usuarios y roles</h5>
                        <p class="text-muted">Mira la lista y cambia roles.</p>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-primary">Ver usuarios</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection