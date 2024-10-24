<?php

namespace Database\Factories;

use App\Models\ConceptoAplicable;
use App\Models\ConceptoCatalogo;
use App\Models\ConvenioCatalogo;
use App\Models\TipoToma;
use App\Models\TipoTomaAplicable;
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
            'pago_inicial' => 10
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
            $conceptos = ConceptoCatalogo::all(); // Asumiendo que el nombre del modelo es ConceptoCatalogo

            foreach ($conceptos as $concepto) {
                ConceptoAplicable::factory()->create([
                    'id_concepto_catalogo' => $concepto->id,
                    'id_modelo' => $convenio->id,
                    'modelo' => 'convenio_catalogo',
                ]);
            }

            $tipo_tomas = TipoToma::all();

            foreach ($tipo_tomas as $tipo_toma) {
                TipoTomaAplicable::factory()->create([
                    'id_tipo_toma' => $tipo_toma->id,
                    'id_modelo' => $convenio->id,
                    'modelo_origen' => 'convenio_catalogo',
                ]);
            }
        });
    }
}
