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
   /**
 * Listar formularios con filtros y ordenación combinables
 * 
 * Filtros: estado, tipo, agente, rango de fechas, periodo (semana/mes)
 * Ordenación: por fecha, título, tipo o usuario, ascendente o descendente
 */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Formulario::with(['usuario', 'tipoFormulario', 'respuestas.campo', 'imagenes']);

        if ($user->rol->nombre === 'agente') {
            $query->where('usuario_id', $user->id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo_formulario_id')) {
            $query->where('tipo_formulario_id', $request->tipo_formulario_id);
        }

        if ($request->filled('usuario_id') && $user->rol->nombre !== 'agente') {
            $query->where('usuario_id', $request->usuario_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        if ($request->filled('periodo')) {
            if ($request->periodo === 'semana') {
                $query->where('created_at', '>=', now()->subWeek());
            } elseif ($request->periodo === 'mes') {
                $query->where('created_at', '>=', now()->subMonth());
            }
        }

        //Tipo de orden

        $ordenarPor = $request->input('ordenar_por', 'created_at');
        $direccion  = $request->input('direccion', 'desc');
        $direccion = in_array($direccion, ['asc', 'desc']) ? $direccion : 'desc';

        if ($ordenarPor === 'titulo') {
            $query->orderBy('titulo', $direccion);
        } elseif ($ordenarPor === 'tipo') {
            $query->join('tipos_formularios', 'formularios.tipo_formulario_id', '=', 'tipos_formularios.id')
                ->orderBy('tipos_formularios.nombre', $direccion)
                ->select('formularios.*');
        } elseif ($ordenarPor === 'usuario') {
            $query->join('users', 'formularios.usuario_id', '=', 'users.id')
                ->orderBy('users.nombre', $direccion)
                ->select('formularios.*');
        } else {
            $query->orderBy('created_at', $direccion);
        }

        return response()->json($query->get());
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

    //para mas estadisticas
    public function estadisticas(Request $request)
    {
        $user = $request->user();
        $query = Formulario::query();

        if ($user->rol->nombre === 'agente') {
            $query->where('usuario_id', $user->id);
        }

        $totales = [
            'total'    => (clone $query)->count(),
            'borrador' => (clone $query)->where('estado', 'borrador')->count(),
            'enviado'  => (clone $query)->where('estado', 'enviado')->count(),
            'valido'   => (clone $query)->where('estado', 'valido')->count(),
            'denegado' => (clone $query)->where('estado', 'denegado')->count(),
        ];

        $ultimos = (clone $query)
            ->with(['usuario', 'tipoFormulario'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'totales' => $totales,
            'ultimos' => $ultimos,
        ]);
    }
    
    //opcion de filtro
    public function agentes()
    {
        $agentes = \App\Models\User::whereHas('rol', function ($q) {
            $q->where('nombre', 'agente');
        })->get(['id', 'nombre', 'apellidos']);

        return response()->json($agentes);
    }
}