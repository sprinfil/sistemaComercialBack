<?php

namespace Database\Factories;

use App\Models\ConceptoAplicable;
use App\Models\ConvenioCatalogo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AjusteCatalogo>
 */
class ConvenioCatalogoFactory extends Factory
{
    protected $model = ConvenioCatalogo::class;
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
        return $this->afterCreating(function (ConvenioCatalogo $convenio) {
            for ($i = 1; $i <= 11; $i++) {
                ConceptoAplicable::factory()->create([
                    'id_concepto_catalogo'=>$i,
                    'id_modelo'=>$convenio->id,
                    'modelo'=>'convenio_catalogo',
                ]); 
            }            
        });
    }
}
