<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Modelo Respuesta
 * 
 * Almacena el valor introducido por el usuario en cada campo.
 * La combinación (formulario_id, campo_id) es única.
 */
class Respuesta extends Model
{
    protected $table = 'respuestas';
    protected $fillable = ['valor', 'formulario_id', 'campo_id'];

    public function formulario() { return $this->belongsTo(Formulario::class, 'formulario_id'); }
    public function campo() { return $this->belongsTo(Campo::class, 'campo_id'); }
}
