<?php

namespace Database\Seeders;

use App\Models\EntityType;
use Illuminate\Database\Seeder;

class EntityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entityTypes = [
            [
                'name' => 'Ministerio',
                'description' => 'Entidad gubernamental de nivel ministerial',
            ],
            [
                'name' => 'Viceministerio',
                'description' => 'Entidad gubernamental de nivel viceministerial',
            ],
            [
                'name' => 'Dirección General',
                'description' => 'Dirección general dentro de un ministerio',
            ],
            [
                'name' => 'Unidad',
                'description' => 'Unidad operativa dentro de una dirección',
            ],
            [
                'name' => 'Empresa Pública',
                'description' => 'Empresa del sector público',
            ],
            [
                'name' => 'Municipio',
                'description' => 'Gobierno municipal',
            ],
            [
                'name' => 'Gobernación',
                'description' => 'Gobierno departamental',
            ],
        ];

        foreach ($entityTypes as $entityType) {
            EntityType::create($entityType);
        }
    }
}