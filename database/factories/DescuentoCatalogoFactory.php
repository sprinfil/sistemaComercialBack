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
            for ($i = 1; $i <= 11; $i++) {
                ConceptoAplicable::factory()->create([
                    'id_concepto_catalogo'=>$i,
                    'id_modelo'=>$descuento->id,
                    'modelo'=>'descuento_catalogo',
                ]); 
            }            
        });
    }
}
