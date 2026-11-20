<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    public function showRegister()
    {
        return view('auth.registro');
    }

    public function register(Request $request)
{
    $datos = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
    ], [
        'name.required' => 'Escribe tu nombre.',
        'name.max' => 'El nombre no puede tener más de 255 caracteres.',
        'email.required' => 'Escribe tu correo electrónico.',
        'email.email' => 'Escribe un correo válido, por ejemplo nombre@correo.com.',
        'email.unique' => 'Este correo ya está registrado.',
        'password.required' => 'Escribe una contraseña.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'password_confirmation.required' => 'Confirma tu contraseña.',
    ]);

    // El rol lo fijamos nosotros, nunca viene del formulario
    $user = User::create([
        'name' => $datos['name'],
        'email' => $datos['email'],
        'password' => $datos['password'],
        'role' => 'empleado',
    ]);

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->route('home');
}
}