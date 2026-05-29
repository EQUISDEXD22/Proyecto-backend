<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Modelo Fichaje
 * 
 * Registra las entradas y salidas de los usuarios.
 * Cada fichaje almacena el tipo (entrada o salida), el usuario
 * que lo realizó y la hora del registro.
 */
class Fichaje extends Model
{
    protected $table = 'fichajes';
    protected $fillable = ['tipo', 'usuario_id'];

    public function usuario() { return $this->belongsTo(User::class, 'usuario_id'); }
}
