<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
/**
 * Controlador AuditoriaController
 * 
 * Auditoría registra  todas las acciones
 * realizadas sobre los formularios.
 */

class AuditoriaController extends Controller
{
    //listado
    public function index(Request $request)
    {
        $auditoria = Auditoria::with(['usuario', 'formulario'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($auditoria);
    }
    //listado por formulario
    public function porFormulario($id)
    {
        $auditoria = Auditoria::with('usuario')
            ->where('formulario_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($auditoria);
    }
}