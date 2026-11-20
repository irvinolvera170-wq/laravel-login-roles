<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = User::orderBy('name')->paginate(10);
        $enPapelera = $request->user()->esAdmin() ? User::onlyTrashed()->count() : 0;

        return view('usuarios.index', compact('usuarios', 'enPapelera'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(UsuarioRequest $request)
    {
        $datos = $request->validated();
        // Un empleado nunca puede elegir el rol: siempre se crea como empleado
        $datos['role'] = $request->user()->esAdmin() ? $datos['role'] : 'empleado';

        User::create($datos);

        return redirect()->route('usuarios.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function show(User $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Request $request, User $usuario)
    {
        $this->autorizarEdicion($request, $usuario);

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(UsuarioRequest $request, User $usuario)
    {
        $this->autorizarEdicion($request, $usuario);

        $datos = $request->validated();

        if (empty($datos['password'])) {
            unset($datos['password']);   // contraseña en blanco = no cambiarla
        }

        // Solo un admin cambia roles, y nunca el suyo propio
        if (!$request->user()->esAdmin() || $usuario->is($request->user())) {
            unset($datos['role']);
        }

        $usuario->update($datos);

        return redirect()->route('usuarios.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function papelera()
    {
        $usuarios = User::onlyTrashed()->orderByDesc('deleted_at')->paginate(10);

        return view('usuarios.papelera', compact('usuarios'));
    }

    // Eliminación lógica (Soft Delete)
    public function destroy(Request $request, User $usuario)
    {
        if ($usuario->is($request->user())) {
            return back()->withErrors(['usuario' => 'No puedes eliminar tu propia cuenta.']);
        }

        $usuario->delete();
        DB::table('sessions')->where('user_id', $usuario->id)->delete();

        return redirect()->route('usuarios.index')
            ->with('status', "{$usuario->name} fue deshabilitado. Puedes restaurarlo desde la papelera.");
    }

    public function restore(User $usuario)
    {
        $usuario->restore();

        return redirect()->route('usuarios.papelera')
            ->with('status', "{$usuario->name} fue restaurado.");
    }

    // Eliminación permanente (Delete)
    public function forceDelete(Request $request, User $usuario)
    {
        if ($usuario->is($request->user())) {
            return back()->withErrors(['usuario' => 'No puedes eliminar tu propia cuenta.']);
        }

        DB::table('sessions')->where('user_id', $usuario->id)->delete();
        $usuario->forceDelete();

        return back()->with('status', "{$usuario->name} fue eliminado permanentemente.");
    }

    private function autorizarEdicion(Request $request, User $usuario): void
    {
        abort_if(
            !$request->user()->esAdmin() && $usuario->esAdmin(),
            403,
            'Un empleado no puede modificar cuentas de administrador.'
        );
    }
}