<?php

namespace Database\Factories;

use App\Models\Factibilidad;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Factibilidad>
 */
class FactibilidadFactory extends Factory
{
    protected $model = Factibilidad::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_contrato'=>1,
            'agua_estado_factible'=> 'factible',//['no_factible', 'factible'],
            'alc_estado_factible'=> 'factible',//['no_factible', 'factible'],
            'derechos_conexion' =>0
        ];
    }
}
