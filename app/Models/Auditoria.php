<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $table = 'auditoria';
    protected $fillable = ['accion', 'descripcion', 'formulario_id', 'usuario_id'];

    public function formulario() { return $this->belongsTo(Formulario::class, 'formulario_id'); }
    public function usuario() { return $this->belongsTo(User::class, 'usuario_id'); }
}
