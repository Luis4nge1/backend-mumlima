<?php

namespace Database\Factories;

use App\Models\Distribucion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distribucion>
 */
class DistribucionFactory extends Factory
{
    protected $model = Distribucion::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => 'Distribución ' . fake()->city(),
            'description' => fake()->sentence(10),
        ];
    }
}