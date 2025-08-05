<?php

namespace Database\Seeders;

use App\Models\Distribucion;
use Illuminate\Database\Seeder;

class DistribucionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $distribuciones = [
            [
                'name' => 'Distribución Norte',
                'description' => 'Distribución que cubre la zona norte de la ciudad',
            ],
            [
                'name' => 'Distribución Sur',
                'description' => 'Distribución que cubre la zona sur de la ciudad',
            ],
            [
                'name' => 'Distribución Este',
                'description' => 'Distribución que cubre la zona este de la ciudad',
            ],
            [
                'name' => 'Distribución Oeste',
                'description' => 'Distribución que cubre la zona oeste de la ciudad',
            ],
            [
                'name' => 'Distribución Centro',
                'description' => 'Distribución que cubre el centro de la ciudad',
            ],
        ];

        foreach ($distribuciones as $distribucion) {
            Distribucion::create($distribucion);
        }
    }
}