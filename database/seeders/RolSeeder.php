<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        Rol::create(['nombre' => 'admin', 'descripcion' => 'Administrador del sistema']);
        Rol::create(['nombre' => 'supervisor', 'descripcion' => 'Supervisor de agentes']);
        Rol::create(['nombre' => 'agente', 'descripcion' => 'Agente policial']);
    }
}
