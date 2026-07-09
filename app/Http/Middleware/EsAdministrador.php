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
        if (!auth()->check() || !auth()->user()->is_admin) {
            return redirect()->route('inicio')
                ->with('error', 'Acceso denegado. No tienes permisos de administrador.');
        }

        return $next($request);
    }
}
