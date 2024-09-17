<?php

namespace Database\Factories;

use App\Models\ConceptoAplicable;
use App\Models\ConceptoCatalogo;
use App\Models\DescuentoCatalogo;
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
            $conceptos = ConceptoCatalogo::all(); // Asumiendo que el nombre del modelo es ConceptoCatalogo

            foreach ($conceptos as $concepto) {
                ConceptoAplicable::factory()->create([
                    'id_concepto_catalogo'=>$concepto->id,
                    'id_modelo'=>$descuento->id,
                    'modelo'=>'descuento_catalogo',
                ]);
            }            
        });
    }
}
