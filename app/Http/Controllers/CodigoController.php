<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CodigoController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();
        $this->bloquearSiNoCorresponde($user);

        $segundos = $user->codigo_expira_at
            ? max(0, $user->codigo_expira_at->timestamp - now()->timestamp)
            : 0;

        // no-store: el navegador no guarda esta pantalla, así que "atrás" la vuelve a pedir
        return response()
            ->view('auth.verificar', compact('segundos'))
            ->withHeaders([
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
    }

    public function verificar(Request $request)
    {
        $user = Auth::user();
        $this->bloquearSiNoCorresponde($user);

        $validador = Validator::make($request->all(), [
            'codigo' => 'required|digits:6',
        ], [
            'codigo.required' => 'Escribe el código que te enviamos al correo.',
            'codigo.digits' => 'El código debe tener exactamente 6 números.',
        ]);

        if ($validador->fails()) {
            return redirect($user->urlVerificacion())->withErrors($validador);
        }

        if (!$user->codigo || now()->gt($user->codigo_expira_at)) {
            return redirect($user->urlVerificacion())
                ->withErrors(['codigo' => 'El código expiró. Presiona "Reenviar código" para recibir uno nuevo.']);
        }

        if (!Hash::check($request->codigo, $user->codigo)) {
            return redirect($user->urlVerificacion())
                ->withErrors(['codigo' => 'El código es incorrecto. Revisa tu correo e inténtalo de nuevo.']);
        }

        $user->update(['codigo' => null, 'codigo_expira_at' => null]);
        session(['codigo_verificado' => true]);
        $request->session()->regenerate();   // nuevo ID de sesión al subir de privilegio

        return redirect()->route('home');
    }

    public function reenviar()
    {
        $user = Auth::user();
        $this->bloquearSiNoCorresponde($user);

        $user->enviarCodigo();

        return redirect($user->urlVerificacion())->with('status', 'Te enviamos un nuevo código.');
    }

    // Si no es admin o ya verificó el código, la ruta "no existe" (404)
    private function bloquearSiNoCorresponde($user): void
    {
        abort_if(!$user->esAdmin() || session('codigo_verificado'), 404);
    }
}