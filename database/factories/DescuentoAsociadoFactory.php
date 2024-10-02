<?php

namespace Database\Factories;

use App\Models\DescuentoAsociado;
use App\Models\DescuentoCatalogo;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DescuentoAsociado>
 */
class DescuentoAsociadoFactory extends Factory
{
    protected $model = DescuentoAsociado::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $id_usuario = 0;
        $id_usuario++;
        return [
            'id_descuento' => DescuentoCatalogo::inRandomOrder()->first()->id,
            'id_modelo' => 1,
            'modelo_dueno' => 'toma',
            'id_evidencia' => $this->faker->optional()->randomDigitNotNull,
            'id_registra' => Usuario::find($id_usuario)->tomas->first()->id,
            'vigencia' => now(),
            'estatus' => 'vigente',
            'folio' => Str::random(10),
        ];
    }
}
