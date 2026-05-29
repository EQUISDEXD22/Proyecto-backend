<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador UsuarioController
 * 
 * Gestiona las operaciones CRUD sobre usuarios.
 * Solo accesible para usuarios con rol administrador.
 
 */
class UsuarioController extends Controller
{
    //listar usuarios
    public function index()
    {
        return response()->json(User::with('rol')->get());
    }

    public function show($id)
    {
        $usuario = User::with('rol')->findOrFail($id);
        return response()->json($usuario);
    }

    //crear
    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6',
            'rol_id'    => 'required|exists:roles,id',
        ]);
        $usuario = User::create([
            'nombre'    => $request->nombre,
            'apellidos' => $request->apellidos,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'activo'    => true,
            'rol_id'    => $request->rol_id,
        ]);

        return response()->json($usuario->load('rol'), 201);
    }
    //update
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre'    => 'sometimes|string|max:100',
            'apellidos' => 'sometimes|string|max:100',
            'email'     => 'sometimes|email|unique:users,email,' . $id,
            'password'  => 'sometimes|min:6',
            'rol_id'    => 'sometimes|exists:roles,id',
            'activo'    => 'sometimes|boolean',
        ]);

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $usuario->update($request->all());
        return response()->json($usuario->load('rol'));
    }
    //desactivar cuenta
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->activo = false;
        $usuario->save();
        return response()->json(['message' => 'Usuario desactivado correctamente']);
    }

    public function roles()
    {
        return response()->json(Rol::all());
    }
    //reactivar
    public function activar($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->activo = true;
        $usuario->save();
        return response()->json(['message' => 'Usuario activado correctamente']);
    }
}