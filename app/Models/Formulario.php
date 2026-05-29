<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Formulario extends Model
{
    protected $fillable = ['titulo', 'estado', 'comentarios', 'usuario_id', 'tipo_formulario_id'];

    public function usuario() { return $this->belongsTo(User::class, 'usuario_id'); }
    public function tipoFormulario() { return $this->belongsTo(TipoFormulario::class, 'tipo_formulario_id'); }
    public function respuestas() { return $this->hasMany(Respuesta::class, 'formulario_id'); }
    public function auditoria() { return $this->hasMany(Auditoria::class, 'formulario_id'); }
}
