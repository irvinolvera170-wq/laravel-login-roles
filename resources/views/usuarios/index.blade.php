@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h3 class="mb-0">Usuarios</h3>
                <div class="d-flex gap-2">
                    @if (auth()->user()->esAdmin())
                        <a href="{{ route('usuarios.papelera') }}" class="btn btn-outline-warning btn-sm">
                            Papelera ({{ $enPapelera }})
                        </a>
                    @endif
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm">+ Nuevo usuario</a>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">← Home</a>
                </div>
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
                            <th>Registro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $u)
                            <tr>
                                <td>{{ $u->name }} @if ($u->id === auth()->id()) <span class="text-muted">(tú)</span> @endif</td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    <span class="badge {{ $u->esAdmin() ? 'bg-danger' : 'bg-secondary' }}">{{ $u->role }}</span>
                                </td>
                                <td>{{ $u->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('usuarios.show', $u) }}" class="btn btn-sm btn-outline-info">Ver</a>

                                        @if (auth()->user()->esAdmin() || !$u->esAdmin())
                                            <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                        @endif

                                        @if (auth()->user()->esAdmin() && $u->id !== auth()->id())
                                            <form method="POST" action="{{ route('usuarios.destroy', $u) }}"
                                                  onsubmit="return confirm('¿Deshabilitar este usuario? Podrás restaurarlo desde la papelera.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-warning">Deshabilitar</button>
                                            </form>
                                            <form method="POST" action="{{ route('usuarios.forceDelete', $u) }}"
                                                  onsubmit="return confirm('¿Eliminar PERMANENTEMENTE? Esta acción no se puede deshacer.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted">No hay usuarios.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $usuarios->links() }}
        </div>
    </div>
</div>
@endsection