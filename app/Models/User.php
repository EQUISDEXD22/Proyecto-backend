<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modelo User
 * 
 * usuarios del sistema .
 * Authenticatable para permitir la autenticación con Laravel Sanctum
 * Usa HasApiTokens para generar y gestionar tokens de acceso
 */

class User extends Authenticatable
{
    use HasApiTokens;
    
    protected $fillable = ['nombre', 'apellidos', 'email', 'password', 'activo', 'rol_id'];
    // Campos que se ocultan por seguridad
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * Relación con el modelo rol
     * Un usuario pertenece a un único rol
     */
    public function rol() { return $this->belongsTo(Rol::class, 'rol_id'); }
    /**
     * Relación con el modelo formulario
     * Un usuario puede crear múltiples formularios
     */
    public function formularios() { return $this->hasMany(Formulario::class, 'usuario_id'); }
    /** 
     * Relación con el modelo Fichaje
     * Un usuario puede tener múltiples registros de fichaje
     */
    public function fichajes() { return $this->hasMany(Fichaje::class, 'usuario_id'); }
    /**
     * Relación con el modelo Auditoria
     * Un usuario puede generar múltiples registros de auditoría
     */
    public function auditoria() { return $this->hasMany(Auditoria::class, 'usuario_id'); }

}