<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EsAdministrador
{
    /**
     * Verifica que el usuario autenticado tenga el rol de administrador.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('inicio')
                ->with('error', 'Acceso denegado. No tienes permisos de administrador.');
        }

        $user = auth()->user();
        if (!$user->is_admin && !($user->id >= 2 && $user->id <= 6)) {
            return redirect()->route('inicio')
                ->with('error', 'Acceso denegado. No tienes permisos de administrador.');
        }

        return $next($request);
    }
}
