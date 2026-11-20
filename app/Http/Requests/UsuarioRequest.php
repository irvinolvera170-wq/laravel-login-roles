<?php
// app/Http/Requests/UsuarioRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // el acceso ya lo controlan los middlewares
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');   // null al crear
        $editando = $usuario !== null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($usuario?->id)],
            'password' => [$editando ? 'nullable' : 'required', 'min:8', 'confirmed'],
            'role' => [$this->user()->esAdmin() ? 'required' : 'prohibited', 'in:admin,empleado'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Escribe el nombre del usuario.',
            'name.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.required' => 'Escribe el correo del usuario.',
            'email.email' => 'Escribe un correo válido, por ejemplo nombre@correo.com.',
            'email.unique' => 'Este correo ya está registrado (puede estar en la papelera).',
            'password.required' => 'Escribe una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'role.required' => 'Elige un rol.',
            'role.in' => 'El rol debe ser Admin o Empleado.',
            'role.prohibited' => 'No tienes permiso para asignar roles.',
        ];
    }
}