<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $pendingOrders = 0;
        if ($request->user()) {
            $pendingOrders = \App\Models\OrdenTrabajo::where('estado', 'PENDIENTE')->count();
        }

        $roles = [];
        if ($request->user() && $request->user()->rol_id) {
            $rol = \Illuminate\Support\Facades\DB::table('roles')->where('id', $request->user()->rol_id)->first();
            if ($rol) {
                $roles[] = $rol->nombre;
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                // Inyectamos roles/permisos globales para que React los evalúe sin llamadas extra
                'roles' => $roles,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'stats' => [
                'pendingOrders' => fn () => $pendingOrders,
            ],
        ];
    }
}
