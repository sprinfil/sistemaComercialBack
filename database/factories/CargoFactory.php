<?php

namespace Database\Factories;

use App\Models\Cargo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cargo>
 */
class CargoFactory extends Factory
{
    protected $model = Cargo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_origen' => $this->faker->numberBetween(1, 100),
            'modelo_origen' => $this->faker->word,
            'id_dueño' => $this->faker->numberBetween(1, 100),
            'modelo_dueño' => $this->faker->word,
            'monto' => $this->faker->randomFloat(2, 0, 9999),
            'estado' => $this->faker->randomElement(['pendiente', 'pagado', 'cancelado']),
            'fecha_cargo' => now(),
            'fecha_liquidacion' => now()->addDays(1),
            'deleted_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
