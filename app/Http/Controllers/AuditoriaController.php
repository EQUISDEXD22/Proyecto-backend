<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $auditoria = Auditoria::with(['usuario', 'formulario'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($auditoria);
    }

    public function porFormulario($id)
    {
        $auditoria = Auditoria::with('usuario')
            ->where('formulario_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($auditoria);
    }
}