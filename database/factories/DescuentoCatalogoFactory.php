<?php

namespace Database\Factories;

use App\Models\ConceptoAplicable;
use App\Models\ConceptoCatalogo;
use App\Models\DescuentoCatalogo;
use App\Models\TipoToma;
use App\Models\TipoTomaAplicable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DescuentoCatalogo>
 */
class DescuentoCatalogoFactory extends Factory
{
    protected $model = DescuentoCatalogo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (DescuentoCatalogo $descuento) {
            $conceptos = ConceptoCatalogo::all();

            foreach ($conceptos as $concepto) {
                ConceptoAplicable::factory()->create([
                    'id_concepto_catalogo' => $concepto->id,
                    'id_modelo' => $descuento->id,
                    'modelo' => 'descuento_catalogo',
                ]);
            }

            $tipo_tomas = TipoToma::all();

            foreach ($tipo_tomas as $tipo_toma) {
                TipoTomaAplicable::factory()->create([
                    'id_tipo_toma' => $tipo_toma->id,
                    'id_modelo' => $descuento->id,
                    'modelo_origen' => 'descuento_catalogo',
                ]);
            }
        });
    }
}
