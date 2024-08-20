<?php

namespace Database\Factories;

use App\Models\ConceptoCatalogo;
use App\Models\TarifaConceptoDetalle;
use App\Models\TipoToma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConceptoCatalogo>
 */
class ConceptoCatalogoFactory extends Factory
{
    protected $model = ConceptoCatalogo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre'=>$this->faker->word,
            'descripcion'=> $this->faker->sentence,
            'estado'=> $this->faker->randomElement(['activo', 'inactivo']),
            'prioridad_abono'=>$this->faker->numberBetween(0, 5),
            'prioridad_por_antiguedad'=>0,
            'genera_iva'=>0,
            'abonable'=>0,
            'tarifa_fija'=>0,
            'genera_orden'=>0,
            "genera_recargo"=>0,
            "concepto_rezago"=>0,
            "pide_monto"=>0,
            "bonificable"=>0,
            "recargo"=>3,
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (ConceptoCatalogo $concepto) {
            /*$tipo_tarifa = $this->faker->randomElement(['servicio', 'concepto']);

            if($tipo_tarifa == 'servicio')
            {
                $tipo_tomas = TipoToma::all();

                $tipo_tomas->each(function ($tipo_toma) {
                    TarifaServiciosDetalle::factory()->create([
                        'id_tarifa'=>1,
                        'id_tipo_toma'=>$tipo_toma->id,
                        'rango'=>17*$tipo_toma->id,
                        'agua'=>10*17,
                        'alcantarillado'=>2*17,
                        'saneamiento'=>2*17
                    ]);
                });
            } else {
                
            }*/
            $tipo_tomas = TipoToma::all();

                $tipo_tomas->each(function ($tipo_toma) use ($concepto){
                    TarifaConceptoDetalle::factory()->create([
                        'id_tipo_toma' => $tipo_toma->id,
                        'id_concepto' => $concepto->id,
                        'monto' => $this->faker->numberBetween(50, 500),
                    ]);
                });
        });
    }
}
