@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Usuarios</h3>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">← Volver al Home</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @error('role')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    <span class="badge {{ $u->esAdmin() ? 'bg-danger' : 'bg-secondary' }}">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td>
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="{{ route('usuarios.rol', $u) }}">
                                            @csrf
                                            @method('PATCH')
                                            @if ($u->esAdmin())
                                                <input type="hidden" name="role" value="empleado">
                                                <button class="btn btn-sm btn-outline-secondary">Hacer empleado</button>
                                            @else
                                                <input type="hidden" name="role" value="admin">
                                                <button class="btn btn-sm btn-primary">Hacer admin</button>
                                            @endif
                                        </form>
                                    @else
                                        <span class="text-muted">(tú)</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection