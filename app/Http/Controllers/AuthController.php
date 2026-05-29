<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
/**
 * Controlador AuthController
 * 
 * Gestion de autenticación de usuarios en la aplicación.
 * Proporciona tres endpoints:
 * - login: verifica credenciales y devuelve un token 
 * - logout: invalida el token de la sesión actual
 * - me: devuelve los datos del usuario
 * 
 * La autenticación se basa en tokens.
 * Cada petición autenticada debe incluir el token en la cabecera
 */
class AuthController extends Controller
{
    public function login(Request $request)
    {
        //verificacion email + contraseña
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        $usuario = User::where('email', $request->email)->first();
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
        //si esta activo
        if (!$usuario->activo) {
            return response()->json(['message' => 'Usuario desactivado'], 403);
        }
        //generar token
        $token = $usuario->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user'  => [
                'id'        => $usuario->id,
                'nombre'    => $usuario->nombre,
                'apellidos' => $usuario->apellidos,
                'email'     => $usuario->email,
                'rol'       => $usuario->rol->nombre,
            ]
        ]);
    }
    //cierre de sesion
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesion cerrada correctamente']);
    }
    // funcion para obtener usuario
    public function me(Request $request)
    {
        $usuario = $request->user()->load('rol');
        return response()->json([
            'id'        => $usuario->id,
            'nombre'    => $usuario->nombre,
            'apellidos' => $usuario->apellidos,
            'email'     => $usuario->email,
            'rol'       => $usuario->rol->nombre,
        ]);
    }
}
