@extends('layouts.app')

@section('title', 'Papelera de usuarios')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Papelera de usuarios</h3>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-sm">← Volver a usuarios</a>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @error('usuario')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Deshabilitado el</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td><span class="badge bg-secondary">{{ $u->role }}</span></td>
                                <td>{{ $u->deleted_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <form method="POST" action="{{ route('usuarios.restore', $u) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-success">Restaurar</button>
                                        </form>
                                        <form method="POST" action="{{ route('usuarios.forceDelete', $u) }}"
                                              onsubmit="return confirm('¿Eliminar PERMANENTEMENTE? Esta acción no se puede deshacer.')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Eliminar definitivamente</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">La papelera está vacía.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection