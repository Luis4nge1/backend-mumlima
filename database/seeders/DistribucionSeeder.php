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
                'name' => 'Macrozona Norte',
                'description' => 'Macrozona que cubre la zona norte de la ciudad',
            ],
            [
                'name' => 'Macrozona Sur',
                'description' => 'Macrozona que cubre la zona sur de la ciudad',
            ],
            [
                'name' => 'Macrozona Este',
                'description' => 'Macrozona que cubre la zona este de la ciudad',
            ],
            [
                'name' => 'Macrozona Oeste',
                'description' => 'Macrozona que cubre la zona oeste de la ciudad',
            ],
            [
                'name' => 'Macrozona Centro',
                'description' => 'Macrozona que cubre el centro de la ciudad',
            ],
        ];

        foreach ($distribuciones as $distribucion) {
            Distribucion::create($distribucion);
        }
    }
}