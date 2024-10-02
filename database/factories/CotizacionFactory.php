<?php

namespace Database\Factories;

use App\Models\ConceptoCatalogo;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cotizacion>
 */
class CotizacionFactory extends Factory
{
    protected $model = Cotizacion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_contrato'=>0,
            'vigencia'=>'vigente',
            'fecha_inicio'=>now(),
            'fecha_fin'=>date('Y-m-d H:i:s', (strtotime('+1 month', time()))),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Cotizacion $cotizacion) {
            $contrato = $cotizacion->contrato();

            // calculo de costos
            $conceptos = [
                98,
                102,
                103,
                104,
                105,
                132,
                133,
                134,
                135
            ];
            

            // registro detalles
            for ($i = 0; $i <= 3; $i++) {
                $concepto = ConceptoCatalogo::find($conceptos[$i]);
                CotizacionDetalle::factory()->create([
                    'id_cotizacion'=>$cotizacion->id,
                    'id_concepto'=>$conceptos[$i],
                    'monto'=>$concepto->tarifas()->first()->monto,
                ]);
            }            
        });
    }
}
