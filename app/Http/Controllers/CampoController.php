<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campo;

class CampoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'etiqueta'          => 'required|string|max:150',
            'tipo_dato'         => 'required|in:texto,numero,fecha,select,textarea',
            'obligatorio'       => 'boolean',
            'orden'             => 'integer',
            'tipo_formulario_id'=> 'required|exists:tipos_formularios,id',
        ]);

        $campo = Campo::create($request->all());
        return response()->json($campo, 201);
    }

    public function update(Request $request, $id)
    {
        $campo = Campo::findOrFail($id);
        $campo->update($request->all());
        return response()->json($campo);
    }

    public function destroy($id)
    {
        Campo::findOrFail($id)->delete();
        return response()->json(['message' => 'Campo eliminado']);
    }
}