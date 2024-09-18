<?php

namespace Database\Factories;

use App\Models\ConceptoAplicable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConceptoAplicable>
 */
class ConceptoAplicableFactory extends Factory
{
    protected $model = ConceptoAplicable::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_concepto_catalogo' => 0,
            'id_modelo' => 'ninguno',
            'modelo' => 'ninguno',
            'tipo_bonificacion' => $this->faker->randomElement(['porcentual', 'fija']),
            'porcentaje_bonificable' => $this->faker->numberBetween(0, 100),
            'monto_bonificable' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
