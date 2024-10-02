<?php

namespace Database\Factories;

use App\Models\CargaTrabajo;
use App\Models\Operador;
use App\Models\Periodo;
use App\Models\Toma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lectura>
 */
class LecturaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_operador'=> Operador::all()->random()->id,
            'id_toma'=> Toma::all()->random()->id,
            'id_periodo'=> Periodo::all()->random()->id,
            'id_anomalia'=> Periodo::all()->random()->id,
            'lectura'=>$this->faker->numberBetween(1,100),
            'comentario'=> Periodo::all()->random()->id

            // $table->unsignedBigInteger('id_operador');
            // $table->unsignedBigInteger('id_toma');
            // $table->unsignedBigInteger('id_periodo');
            // $table->unsignedBigInteger('id_origen')->nullable();
            // $table->string('modelo_origen')->nullable();
            // $table->unsignedBigInteger('id_anomalia')->nullable();
            // $table->integer('lectura')->nullable();
            // $table->string('comentario')->nullable();
        ];
    }
}
