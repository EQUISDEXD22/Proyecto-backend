<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fichaje;

class FichajeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->rol->nombre === 'agente') {
            $fichajes = Fichaje::where('usuario_id', $user->id)
                ->orderBy('created_at', 'desc')->get();
        } else {
            $fichajes = Fichaje::with('usuario')
                ->orderBy('created_at', 'desc')->get();
        }

        return response()->json($fichajes);
    }

    public function fichar(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:entrada,salida',
        ]);

        $fichaje = Fichaje::create([
            'tipo'       => $request->tipo,
            'usuario_id' => $request->user()->id,
        ]);

        return response()->json($fichaje, 201);
    }
}