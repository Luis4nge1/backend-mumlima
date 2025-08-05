<?php

namespace Database\Factories;

use App\Models\Distribucion;
use App\Models\UsuarioFiscalizacion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UsuarioFiscalizacion>
 */
class UsuarioFiscalizacionFactory extends Factory
{
    protected $model = UsuarioFiscalizacion::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'cellphone' => '+591 ' . fake()->numerify('7#######'),
            'status' => fake()->randomElement(['active', 'inactive']),
            'distribucion_id' => Distribucion::factory(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user should be active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the user should be inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}