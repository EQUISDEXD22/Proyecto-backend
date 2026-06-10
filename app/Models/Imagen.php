<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Modelo imagen
 * 
 * La imagen es nviada a la tabla y se 
 * enlaza mediante su id
 */

class Imagen extends Model
{
    protected $table = 'imagenes';
    protected $fillable = ['ruta', 'nombre_original', 'formulario_id'];

    public function formulario()
    {
        return $this->belongsTo(Formulario::class, 'formulario_id');
    }
}