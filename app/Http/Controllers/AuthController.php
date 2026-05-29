<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        $usuario = User::where('email', $request->email)->first();
        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }
        if (!$usuario->activo) {
            return response()->json(['message' => 'Usuario desactivado'], 403);
        }
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

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Sesion cerrada correctamente']);
    }

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
