<?php

namespace Database\Seeders;

use App\Models\Distribucion;
use App\Models\Entity;
use App\Models\UsuarioFiscalizacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioFiscalizacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distribuciones = Distribucion::all();
        $entities = Entity::all();

        if ($distribuciones->isEmpty()) {
            $this->command->warn('No hay distribuciones disponibles. Ejecuta primero DistribucionSeeder.');
            return;
        }

        if ($entities->isEmpty()) {
            $this->command->warn('No hay entidades disponibles. Ejecuta primero EntitySeeder.');
            return;
        }

        $usuarios = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@fiscalizacion.com',
                'password' => Hash::make('Password123'),
                'cellphone' => '+591 70123456',
                'status' => 'active',
                'distribucion_id' => $distribuciones->random()->id,
                'entity_id' => $entities->random()->id,
            ],
            [
                'name' => 'María García',
                'email' => 'maria.garcia@fiscalizacion.com',
                'password' => Hash::make('Password123'),
                'cellphone' => '+591 71234567',
                'status' => 'active',
                'distribucion_id' => $distribuciones->random()->id,
                'entity_id' => $entities->random()->id,
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos.lopez@fiscalizacion.com',
                'password' => Hash::make('Password123'),
                'cellphone' => '+591 72345678',
                'status' => 'active',
                'distribucion_id' => $distribuciones->random()->id,
                'entity_id' => $entities->random()->id,
            ],
            [
                'name' => 'Ana Rodríguez',
                'email' => 'ana.rodriguez@fiscalizacion.com',
                'password' => Hash::make('Password123'),
                'cellphone' => '+591 73456789',
                'status' => 'inactive',
                'distribucion_id' => $distribuciones->random()->id,
                'entity_id' => null, // Este usuario no tiene entidad asignada
            ],
            [
                'name' => 'Luis Martínez',
                'email' => 'luis.martinez@fiscalizacion.com',
                'password' => Hash::make('Password123'),
                'cellphone' => '+591 74567890',
                'status' => 'active',
                'distribucion_id' => $distribuciones->random()->id,
                'entity_id' => $entities->random()->id,
            ],
        ];

        foreach ($usuarios as $usuario) {
            UsuarioFiscalizacion::create($usuario);
        }
    }
}