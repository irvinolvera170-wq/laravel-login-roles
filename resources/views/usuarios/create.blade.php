@extends('layouts.app')

@section('title', 'Crear usuario')

@section('content')
<div class="container min-vh-100 d-flex justify-content-center align-items-center py-4">
    <div class="card shadow-sm w-100" style="max-width: 440px;">
        <div class="card-body p-4">
            <h3 class="mb-4">Crear usuario</h3>

            <form method="POST" action="{{ route('usuarios.store') }}" novalidate>
                @csrf
                @include('usuarios._form')
                <button type="submit" class="btn btn-primary w-100">Crear usuario</button>
            </form>

            <a href="{{ route('usuarios.index') }}" class="d-block text-center mt-3">← Volver a usuarios</a>
        </div>
    </div>
</div>
@endsection