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
            'id_toma' => 1,
            'id_solicitante' => 1,
            'id_revisor' => null,
            //'estado'=>'concluida',
            'agua_estado_factible' => 'factible', //['no_factible', 'factible'],
            'alc_estado_factible' => 'factible', //['no_factible', 'factible'],
            //'san_estado_factible'=> 'factible',//['no_factible', 'factible'],
            'derechos_conexion' => 0,
            //'documento' => $this->faker->imageUrl(640, 480, 'cats', true, 'Faker', true)
        ];
    }
}
