<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoFormulario;

/**
 * Controlador TipoFormularioController
 * 
 * Gestiona los tipos de formulario disponibles.
 * Los tipos definen qué campos aparecen al crear un formulario.
 */
class TipoFormularioController extends Controller
{
    //listar
    public function index()
    {
        return response()->json(TipoFormulario::with('campos')->get());
    }
    //obtener tipo por id
    public function show($id)
    {
        $tipo = TipoFormulario::with('campos')->findOrFail($id);
        return response()->json($tipo);
    }
    //crear
    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $tipo = TipoFormulario::create($request->all());
        return response()->json($tipo, 201);
    }
    //update
    public function update(Request $request, $id)
    {
        $tipo = TipoFormulario::findOrFail($id);
        $tipo->update($request->all());
        return response()->json($tipo);
    }
    //eliminar
    public function destroy($id)
    {
        $tipo = TipoFormulario::findOrFail($id);
        $tipo->activo = false;
        $tipo->save();
        return response()->json(['message' => 'Tipo de formulario desactivado']);
    }
}