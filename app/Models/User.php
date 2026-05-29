<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    protected $fillable = ['nombre', 'apellidos', 'email', 'password', 'activo', 'rol_id'];
    protected $hidden = ['password', 'remember_token'];

    public function rol() { return $this->belongsTo(Rol::class, 'rol_id'); }
    public function formularios() { return $this->hasMany(Formulario::class, 'usuario_id'); }
    public function fichajes() { return $this->hasMany(Fichaje::class, 'usuario_id'); }
    public function auditoria() { return $this->hasMany(Auditoria::class, 'usuario_id'); }
}
