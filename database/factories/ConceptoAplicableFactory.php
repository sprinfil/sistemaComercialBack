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
            'id_concepto_catalogo'=>0,
            'id_modelo'=>'ninguno',
            'modelo'=>'ninguno',
            'rango_minimo'=>$this->faker->numberBetween(0, 50),
            'rango_maximo'=>$this->faker->numberBetween(50, 100),
        ];
    }
}
