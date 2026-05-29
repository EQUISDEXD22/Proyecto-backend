<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Modelo Campo
 *
 * Los campos definen qué información tiene que introducir el agente
 * al crear un formulario.
 * Cada campo tiene un tipo de dato (texto, número, fecha, textarea, select)
 * y puede marcarse como obligatorio.
 */
class Campo extends Model
{
    protected $fillable = ['etiqueta', 'tipo_dato', 'obligatorio', 'orden', 'tipo_formulario_id'];
    //Relaciones
    public function tipoFormulario() { return $this->belongsTo(TipoFormulario::class, 'tipo_formulario_id'); }
    public function respuestas() { return $this->hasMany(Respuesta::class, 'campo_id'); }
}
