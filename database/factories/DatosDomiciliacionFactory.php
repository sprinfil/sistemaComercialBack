<?php

namespace Database\Factories;

use App\Models\DatosDomiciliacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DatosDomiciliacion>
 */
class DatosDomiciliacionFactory extends Factory
{
    protected $model = DatosDomiciliacion::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_toma' => 0,
            'numero_cuenta' => 0,
            'fecha_vencimiento' => now(),
            'tipo_tarjeta' => '',
            'limite_cobro' => 999.00,
            'domicilio_tarjeta' => '',
        ];
    }
}
