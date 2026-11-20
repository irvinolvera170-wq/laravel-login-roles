<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SoloAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->esAdmin()) {
            abort(403, 'No tienes permiso para entrar aquí.');
        }

        return $next($request);
    }
}