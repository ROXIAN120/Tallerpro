<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string[] ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user() || ! $request->user()->rol_id) {
            return redirect('/dashboard');
        }

        $userRole = \Illuminate\Support\Facades\DB::table('roles')->where('id', $request->user()->rol_id)->first();

        if (! $userRole || ! in_array($userRole->nombre, $roles)) {
            if ($userRole->nombre === 'Mecanico' || $userRole->nombre === 'Recepcionista') {
                return redirect('/taller/kanban')->with('error', 'No tienes permisos para acceder a este módulo.');
            }
            return redirect('/dashboard')->with('error', 'Acceso denegado.');
        }

        return $next($request);
    }
}
