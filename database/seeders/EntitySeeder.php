<?php

namespace Database\Seeders;

use App\Models\Entity;
use App\Models\EntityType;
use Illuminate\Database\Seeder;

class EntitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entityTypes = EntityType::all();

        if ($entityTypes->isEmpty()) {
            $this->command->warn('No hay tipos de entidad disponibles. Ejecuta primero EntityTypeSeeder.');
            return;
        }

        // Obtener tipos específicos
        $ministerioType = $entityTypes->where('name', 'Ministerio')->first();
        $viceministerioType = $entityTypes->where('name', 'Viceministerio')->first();
        $direccionType = $entityTypes->where('name', 'Dirección General')->first();
        $unidadType = $entityTypes->where('name', 'Unidad')->first();
        $municipioType = $entityTypes->where('name', 'Municipio')->first();

        // Crear entidades padre (Ministerios)
        $ministerioEconomia = Entity::create([
            'parent_id' => null,
            'type_id' => $ministerioType->id,
            'name' => 'Ministerio de Economía y Finanzas Públicas',
            'short_name' => 'MEFP',
            'description' => 'Ministerio encargado de la política económica y financiera del Estado',
            'contact_email' => 'contacto@economiayfinanzas.gob.bo',
            'contact_phone' => '+591 2 2582000',
            'address' => 'Av. Mariscal Santa Cruz, La Paz, Bolivia',
        ]);

        $ministerioObras = Entity::create([
            'parent_id' => null,
            'type_id' => $ministerioType->id,
            'name' => 'Ministerio de Obras Públicas, Servicios y Vivienda',
            'short_name' => 'MOPSV',
            'description' => 'Ministerio encargado de la infraestructura y servicios públicos',
            'contact_email' => 'contacto@oopp.gob.bo',
            'contact_phone' => '+591 2 2156600',
            'address' => 'Av. 20 de Octubre, La Paz, Bolivia',
        ]);

        // Crear entidades hijas (Viceministerios)
        $viceministerioTesoro = Entity::create([
            'parent_id' => $ministerioEconomia->id,
            'type_id' => $viceministerioType->id,
            'name' => 'Viceministerio del Tesoro y Crédito Público',
            'short_name' => 'VTCP',
            'description' => 'Viceministerio encargado de la gestión del tesoro nacional',
            'contact_email' => 'tesoro@economiayfinanzas.gob.bo',
            'contact_phone' => '+591 2 2582100',
            'address' => 'Av. Mariscal Santa Cruz, La Paz, Bolivia',
        ]);

        $viceministerioVivienda = Entity::create([
            'parent_id' => $ministerioObras->id,
            'type_id' => $viceministerioType->id,
            'name' => 'Viceministerio de Vivienda y Urbanismo',
            'short_name' => 'VVU',
            'description' => 'Viceministerio encargado de políticas de vivienda',
            'contact_email' => 'vivienda@oopp.gob.bo',
            'contact_phone' => '+591 2 2156700',
            'address' => 'Av. 20 de Octubre, La Paz, Bolivia',
        ]);

        // Crear direcciones generales
        Entity::create([
            'parent_id' => $viceministerioTesoro->id,
            'type_id' => $direccionType->id,
            'name' => 'Dirección General de Contabilidad Fiscal',
            'short_name' => 'DGCF',
            'description' => 'Dirección encargada de la contabilidad del sector público',
            'contact_email' => 'contabilidad@economiayfinanzas.gob.bo',
            'contact_phone' => '+591 2 2582200',
            'address' => 'Av. Mariscal Santa Cruz, La Paz, Bolivia',
        ]);

        // Crear municipios
        Entity::create([
            'parent_id' => null,
            'type_id' => $municipioType->id,
            'name' => 'Gobierno Autónomo Municipal de La Paz',
            'short_name' => 'GAMLP',
            'description' => 'Gobierno municipal de la ciudad de La Paz',
            'contact_email' => 'contacto@lapaz.bo',
            'contact_phone' => '+591 2 2650000',
            'address' => 'Plaza Murillo, La Paz, Bolivia',
        ]);

        Entity::create([
            'parent_id' => null,
            'type_id' => $municipioType->id,
            'name' => 'Gobierno Autónomo Municipal de Santa Cruz',
            'short_name' => 'GAMSC',
            'description' => 'Gobierno municipal de la ciudad de Santa Cruz',
            'contact_email' => 'contacto@santacruz.gob.bo',
            'contact_phone' => '+591 3 3480000',
            'address' => 'Plaza 24 de Septiembre, Santa Cruz, Bolivia',
        ]);
    }
}