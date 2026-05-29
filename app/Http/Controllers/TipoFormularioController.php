<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoFormulario;

class TipoFormularioController extends Controller
{
    public function index()
    {
        return response()->json(TipoFormulario::with('campos')->get());
    }

    public function show($id)
    {
        $tipo = TipoFormulario::with('campos')->findOrFail($id);
        return response()->json($tipo);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $tipo = TipoFormulario::create($request->all());
        return response()->json($tipo, 201);
    }

    public function update(Request $request, $id)
    {
        $tipo = TipoFormulario::findOrFail($id);
        $tipo->update($request->all());
        return response()->json($tipo);
    }

    public function destroy($id)
    {
        $tipo = TipoFormulario::findOrFail($id);
        $tipo->activo = false;
        $tipo->save();
        return response()->json(['message' => 'Tipo de formulario desactivado']);
    }
}