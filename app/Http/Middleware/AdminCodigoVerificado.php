<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminCodigoVerificado
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->esAdmin() && !session('codigo_verificado')) {
            return redirect($user->urlVerificacion());
        }

        return $next($request);
    }
}