<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Modelo TipoFormulario
 * 
 * Categorías de formularios disponibles en el sistema
 * El administrador puede crear, editar y activar/desactivar tipos.
 * Cada tipo tiene un conjunto de campos definidos que se muestran
 * dinámicamente al crear un formulario.
 */
class TipoFormulario extends Model
{
    protected $table = 'tipos_formularios';
    protected $fillable = ['nombre', 'descripcion', 'activo'];

    public function campos() { return $this->hasMany(Campo::class, 'tipo_formulario_id'); }
    public function formularios() { return $this->hasMany(Formulario::class, 'tipo_formulario_id'); }
}
