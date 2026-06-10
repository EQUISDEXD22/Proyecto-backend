<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333; font-size: 12px; }
        .cabecera { border-bottom: 3px solid #F97316; padding-bottom: 10px; margin-bottom: 20px; }
        .cabecera h1 { color: #F97316; margin: 0; font-size: 22px; }
        .cabecera p { margin: 2px 0; color: #888; font-size: 11px; }
        .datos { margin-bottom: 20px; }
        .datos table { width: 100%; border-collapse: collapse; }
        .datos td { padding: 6px 8px; }
        .datos td.label { font-weight: bold; width: 30%; color: #555; }
        .estado { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 11px; }
        .estado-borrador { background: #eee; color: #555; }
        .estado-enviado { background: #dbeafe; color: #1e40af; }
        .estado-valido { background: #dcfce7; color: #166534; }
        .estado-denegado { background: #fee2e2; color: #991b1b; }
        .campos { margin-top: 20px; }
        .campos h2 { font-size: 14px; color: #F97316; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .campo { margin-bottom: 12px; }
        .campo .etiqueta { font-weight: bold; color: #555; font-size: 11px; }
        .campo .valor { margin-top: 3px; padding: 6px; background: #fafafa; border: 1px solid #eee; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="cabecera">
        <h1>{{ $formulario->titulo }}</h1>
    </div>

    <div class="datos">
        <table>
            <tr>
                <td class="label">Tipo de formulario</td>
                <td>{{ $formulario->tipoFormulario->nombre ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Agente</td>
                <td>{{ $formulario->usuario->nombre ?? '' }} {{ $formulario->usuario->apellidos ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Estado</td>
                <td>
                    <span class="estado estado-{{ $formulario->estado }}">
                        {{ ucfirst($formulario->estado) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td class="label">Fecha</td>
                <td>{{ $formulario->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td class="label">Última actualización</td>
                <td>{{ $formulario->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
            @if($formulario->comentarios)
            <tr>
                <td class="label">Comentarios</td>
                <td>{{ $formulario->comentarios }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="campos">
        <h2>Datos del formulario</h2>
        @forelse($formulario->respuestas as $respuesta)
            <div class="campo">
                <div class="etiqueta">{{ $respuesta->campo->etiqueta ?? 'Campo' }}</div>
                <div class="valor">{{ $respuesta->valor ?: '(sin respuesta)' }}</div>
            </div>
        @empty
            <p>Este formulario no tiene respuestas registradas.</p>
        @endforelse
    </div>
</body>
</html>