<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formulario;
use App\Models\Auditoria;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Controlador FormularioController
 * 
 * Gestiona el ciclo de vida de los formularios.
 * Cada cambio de estado genera un registro  en la tabla auditoría.
 */
class FormularioController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Formulario::with(['usuario', 'tipoFormulario', 'respuestas.campo', 'imagenes']);

        if ($user->rol->nombre === 'agente') {
            $query->where('usuario_id', $user->id);
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('tipo_formulario_id')) {
            $query->where('tipo_formulario_id', $request->tipo_formulario_id);
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $formulario = Formulario::with(['usuario', 'tipoFormulario', 'respuestas.campo', 'imagenes'])->findOrFail($id);
        return response()->json($formulario);
    }
    
    //crear
    public function store(Request $request)
    {
        $request->validate([
            'titulo'             => 'required|string|max:200',
            'tipo_formulario_id' => 'required|exists:tipos_formularios,id',
            'respuestas'         => 'array',
            'respuestas.*.campo_id' => 'required|exists:campos,id',
            'respuestas.*.valor'    => 'nullable|string',
        ]);

        $formulario = Formulario::create([
            'titulo'             => $request->titulo,
            'estado'             => 'borrador',
            'usuario_id'         => $request->user()->id,
            'tipo_formulario_id' => $request->tipo_formulario_id,
        ]);

        if ($request->has('respuestas')) {
            foreach ($request->respuestas as $r) {
                $formulario->respuestas()->create([
                    'campo_id' => $r['campo_id'],
                    'valor'    => $r['valor'] ?? null,
                ]);
            }
        }

        Auditoria::create([
            'accion'       => 'crear',
            'descripcion'  => 'Formulario creado',
            'formulario_id'=> $formulario->id,
            'usuario_id'   => $request->user()->id,
        ]);

        return response()->json($formulario->load(['respuestas.campo', 'tipoFormulario']), 201);
    }
    //update
    public function update(Request $request, $id)
    {
        $formulario = Formulario::findOrFail($id);

        if (in_array($formulario->estado, ['validado', 'denegado'])) {
            return response()->json(['message' => 'No se puede editar un formulario validado o denegado'], 403);
        }

        $formulario->update($request->only(['titulo', 'estado', 'comentarios']));

        if ($request->has('respuestas')) {
            $formulario->respuestas()->delete();
            foreach ($request->respuestas as $r) {
                $formulario->respuestas()->create([
                    'campo_id' => $r['campo_id'],
                    'valor'    => $r['valor'] ?? null,
                ]);
            }
        }

        Auditoria::create([
            'accion'       => 'editar',
            'descripcion'  => 'Formulario actualizado a estado: ' . $formulario->estado,
            'formulario_id'=> $formulario->id,
            'usuario_id'   => $request->user()->id,
        ]);

        return response()->json($formulario->load(['respuestas.campo', 'tipoFormulario']));
    }
    //Cambiar estados a valido o denegado
    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado'      => 'required|in:enviado,valido,denegado',
            'comentarios' => 'nullable|string',
        ]);

        $formulario = Formulario::findOrFail($id);
        $formulario->estado      = $request->estado;
        $formulario->comentarios = $request->comentarios;
        $formulario->save();

        Auditoria::create([
            'accion'       => 'estado',
            'descripcion'  => 'Estado cambiado a: ' . $request->estado,
            'formulario_id'=> $formulario->id,
            'usuario_id'   => $request->user()->id,
        ]);

        return response()->json($formulario);
    }

    //pdf 
    public function exportarPdf($id)
    {
        $formulario = Formulario::with([
            'usuario',
            'tipoFormulario',
            'respuestas.campo',
            'imagenes'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('formulario_pdf', compact('formulario'));

        return $pdf->download('formulario_' . $formulario->id . '.pdf');
    }
}