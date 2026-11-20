<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
{
    $datos = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
        'role' => 'required|in:admin,empleado',
    ], [
        'name.required' => 'Escribe el nombre del usuario.',
        'email.required' => 'Escribe el correo del usuario.',
        'email.email' => 'Escribe un correo válido, por ejemplo nombre@correo.com.',
        'email.unique' => 'Este correo ya está registrado.',
        'password.required' => 'Escribe una contraseña.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.confirmed' => 'Las contraseñas no coinciden.',
        'password_confirmation.required' => 'Confirma la contraseña.',
        'role.required' => 'Elige un rol.',
        'role.in' => 'El rol debe ser Admin o Empleado.',
    ]);

    User::create([
        'name' => $datos['name'],
        'email' => $datos['email'],
        'password' => $datos['password'],
        'role' => $datos['role'],
    ]);

    return redirect()->route('usuarios.create')
        ->with('status', 'Usuario creado correctamente.');
}

   

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:admin,empleado']);

        if ($user->id === $request->user()->id) {
            return back()->withErrors(['role' => 'No puedes cambiar tu propio rol.']);
        }

        $user->update(['role' => $request->role]);

        return back()->with('status', "Rol de {$user->name} actualizado a {$request->role}.");
    }
}