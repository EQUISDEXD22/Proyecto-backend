<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

/**
 * Middleware CheckRol
 * 
 * Verificacion de usuario autorizado
 * verifica que el usuario tiene uno de los roles permitidos para acceder a una ruta concreta.
 * 
 * Se usa en las rutas de la API de dos maneras:
 * Route::middleware('rol:admin') 
 * Route::middleware('rol:admin,supervisor') 
 */
class CheckRol
{
    //acepta peticion y verifica rol
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        //el usuario es autenticado
        if (!$user) {
            return response()->json(['message' => 'No autenticado'], 401);
        }
        //verifica el rol
        if (!in_array($user->rol->nombre, $roles)) {
            return response()->json(['message' => 'No tienes permiso para esta accion'], 403);
        }
        return $next($request);
    }
}
