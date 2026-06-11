<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imagen;
use App\Models\Formulario;
use Illuminate\Support\Facades\Storage;

class ImagenController extends Controller
{
    /**
     * Controlador imagen
     * 
     * Valida que los archivos sean imágenes y no superen un tamaño.
     */
    public function store(Request $request, $formularioId)
    {
        $request->validate([
            'imagenes'   => 'required|array',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg|max:5120', //aqui el tamaño maximo, de momento 5mb
        ]);

        $formulario = Formulario::findOrFail($formularioId);

        if (in_array($formulario->estado, ['valido', 'denegado'])) {
            return response()->json([
                'message' => 'No se pueden añadir si ya esta cerrado'
            ], 403);
        }

        $imagenesGuardadas = [];

        foreach ($request->file('imagenes') as $archivo) {
            $ruta = $archivo->store('formularios', 'public');
            $imagen = Imagen::create([
                'ruta'            => $ruta,
                'nombre_original' => $archivo->getClientOriginalName(),
                'formulario_id'   => $formulario->id,
            ]);

            $imagenesGuardadas[] = $imagen;
        }

        return response()->json($imagenesGuardadas, 201);
    }

    public function destroy($id)
    {
        $imagen = Imagen::findOrFail($id);

        Storage::disk('public')->delete($imagen->ruta);
        $imagen->delete();

        return response()->json(['message' => 'Imagen eliminada']);
    }
}