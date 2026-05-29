<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Rol::where('nombre', 'admin')->first();
        User::create([
            'nombre'    => 'Administrador',
            'apellidos' => 'Sistemas',
            'email'     => 'admin@admin.es',
            'password'  => Hash::make('123456'),
            'activo'    => true,
            'rol_id'    => $admin->id,
        ]);
    }
}
