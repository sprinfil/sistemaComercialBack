<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\Consumo;
use App\Models\Factura;
use App\Models\Periodo;
use App\Models\TarifaServiciosDetalle;
use App\Models\Toma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factura>
 */
class FacturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_periodo'=> 0,//Periodo::all()->random()->id,
            'id_toma'=> 0,//Toma::all()->random()->id,
            'id_consumo'=> 0,//Consumo::all()->random()->id,
            'id_tarifa_servicio'=> 0,//TarifaServiciosDetalle::all()->random()->id,
            'monto'=>$this->faker->numberBetween(1,400),
            'fecha'=>now(),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Factura $factura) 
        {
            Cargo::factory()->create([
                'id_concepto' => 146,
                'nombre' => "Facturacion " . $factura->periodo->nombre,
                'id_origen' => $factura->id,
                'modelo_origen' => 'facturacion',
                'id_dueno' => $factura->id_toma,
                'modelo_dueno' => 'toma',
                'monto' => $factura->monto,
                'iva' => (0.16 * $factura->monto),
                'estado' => 'pendiente',
                'fecha_cargo' => Periodo::find($factura->id_periodo)->periodo,
                'fecha_liquidacion' => null, //$fecha_liquidacion,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}
