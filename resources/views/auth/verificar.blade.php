@extends('layouts.app')

@section('title', 'Verificación de administrador')

@section('content')
<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="card shadow-sm w-100 text-center" style="max-width: 420px;">
        <div class="card-body p-4">
            <div class="display-4">🔐</div>
            <h4 class="mt-2">Verificación de administrador</h4>
            <p class="text-muted">
                Enviamos un código de 6 números a<br>
                <strong>{{ auth()->user()->email }}</strong>
            </p>

            <p class="mb-3">
                Tiempo restante:
                <span id="timer" class="fs-3 fw-bold text-warning">--:--</span>
            </p>

            @if (session('status'))
                <div class="alert alert-success py-2">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('codigo.verificar') }}" novalidate>
                @csrf
                <input type="text" id="codigo" name="codigo" maxlength="6" inputmode="numeric"
                       autocomplete="one-time-code" placeholder="000000"
                       class="form-control form-control-lg text-center @error('codigo') is-invalid @enderror"
                       style="letter-spacing: .5rem;">
                @error('codigo') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <button type="submit" id="btnVerificar" class="btn btn-warning text-white w-100 mt-3">
                    Verificar
                </button>
            </form>

            <form method="POST" action="{{ route('codigo.reenviar') }}">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100 mt-2">Reenviar código</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-link text-muted mt-2">Cancelar y volver al inicio</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const fin = Date.now() + {{ $segundos }} * 1000;
    const timer = document.getElementById('timer');
    const btn = document.getElementById('btnVerificar');
    const input = document.getElementById('codigo');

    function actualizar() {
        const restante = Math.max(0, Math.round((fin - Date.now()) / 1000));
        const m = String(Math.floor(restante / 60)).padStart(2, '0');
        const s = String(restante % 60).padStart(2, '0');
        timer.textContent = m + ':' + s;

        if (restante <= 0) {
            clearInterval(intervalo);
            timer.textContent = 'Código expirado';
            timer.classList.replace('text-warning', 'text-danger');
            btn.disabled = true;
            input.disabled = true;
        }
    }

    const intervalo = setInterval(actualizar, 1000);
    actualizar();
    // Si el navegador restaura esta pantalla desde su caché al dar "atrás", se recarga
    // y el servidor responde 404 porque el admin ya verificó
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) location.reload();
    });
</script>
@endsection