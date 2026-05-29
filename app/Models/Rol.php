<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Rol
 * 
 * Representa los roles del sistema
 * Cada usuario tiene asignado un único rol que determina
 * las acciones que puede realizar.
 */
class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre', 'descripcion'];
 
    public function usuarios()
    {
        return $this->hasMany(User::class, 'rol_id');
    }
}