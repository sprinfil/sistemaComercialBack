<?php

namespace Database\Factories;

use App\Models\AjusteCatalogo;
use App\Models\ConceptoAplicable;
use App\Models\ConceptoCatalogo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AjusteCatalogo>
 */
class AjusteCatalogoFactory extends Factory
{
    protected $model = AjusteCatalogo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => '',
            'descripcion' => '',
            'estado' => '',
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (AjusteCatalogo $ajuste) { 
            $conceptos = ConceptoCatalogo::all(); // Asumiendo que el nombre del modelo es ConceptoCatalogo

            foreach ($conceptos as $concepto) {
                ConceptoAplicable::factory()->create([
                    'id_concepto_catalogo'=>$concepto->id,
                    'id_modelo'=>$ajuste->id,
                    'modelo'=>'ajuste_catalogo',
                ]); 
            }          
        });
    }
}
