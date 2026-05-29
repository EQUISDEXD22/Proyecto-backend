<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TipoFormulario extends Model
{
    protected $table = 'tipos_formularios';
    protected $fillable = ['nombre', 'descripcion', 'activo'];

    public function campos() { return $this->hasMany(Campo::class, 'tipo_formulario_id'); }
    public function formularios() { return $this->hasMany(Formulario::class, 'tipo_formulario_id'); }
}
