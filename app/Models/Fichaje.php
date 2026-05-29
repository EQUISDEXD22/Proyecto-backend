<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Fichaje extends Model
{
    protected $table = 'fichajes';
    protected $fillable = ['tipo', 'usuario_id'];

    public function usuario() { return $this->belongsTo(User::class, 'usuario_id'); }
}
