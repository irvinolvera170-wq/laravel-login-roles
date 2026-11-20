<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CodigoController extends Controller
{
    public function showForm()
{
    $user = Auth::user();

    if (!$user->esAdmin() || session('codigo_verificado')) {
        return redirect()->route('home');
    }

    $segundos = $user->codigo_expira_at
        ? max(0, $user->codigo_expira_at->timestamp - now()->timestamp)
        : 0;

    return view('auth.verificar', compact('segundos'));
}
   public function verificar(Request $request)
{
    $request->validate([
        'codigo' => 'required|digits:6',
    ], [
        'codigo.required' => 'Escribe el código que te enviamos al correo.',
        'codigo.digits' => 'El código debe tener exactamente 6 números.',
    ]);

    $user = Auth::user();

    if (!$user->codigo || now()->gt($user->codigo_expira_at)) {
        return back()->withErrors(['codigo' => 'El código expiró. Presiona "Reenviar código" para recibir uno nuevo.']);
    }

    if (!Hash::check($request->codigo, $user->codigo)) {
        return back()->withErrors(['codigo' => 'El código es incorrecto. Revisa tu correo e inténtalo de nuevo.']);
    }

    $user->update(['codigo' => null, 'codigo_expira_at' => null]);
    session(['codigo_verificado' => true]);

    return redirect()->route('home');
}

    public function reenviar()
    {
        Auth::user()->enviarCodigo();
        return back()->with('status', 'Te enviamos un nuevo código.');
    }
}