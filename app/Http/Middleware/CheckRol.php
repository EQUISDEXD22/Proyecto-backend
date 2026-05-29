<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class CheckRol
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }
        if (!in_array($user->rol->nombre, $roles)) {
            return response()->json(['message' => 'No tienes permiso para esta accion'], 403);
        }
        return $next($request);
    }
}
