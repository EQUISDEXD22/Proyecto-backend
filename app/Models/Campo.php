<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Campo extends Model
{
    protected $fillable = ['etiqueta', 'tipo_dato', 'obligatorio', 'orden', 'tipo_formulario_id'];

    public function tipoFormulario() { return $this->belongsTo(TipoFormulario::class, 'tipo_formulario_id'); }
    public function respuestas() { return $this->hasMany(Respuesta::class, 'campo_id'); }
}
