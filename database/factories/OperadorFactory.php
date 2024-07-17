<?php

namespace Database\Factories;

use App\Models\Operador;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Operador>
 */
class OperadorFactory extends Factory
{
    protected $model = Operador::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $id_operadores = 0;
        $id_operadores++;
        
        return [
            'codigo_empleado'=>$id_operadores,
            'nombre'=>$this->faker->firstName,
            'apellido_paterno' => $this->faker->lastName,
            'apellido_materno'=> $this->faker->lastName,
            'CURP'=> strtoupper($this->faker->bothify('????######??????##')),
            'fecha_nacimiento'=>now(),
        ];
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Operador $operador) {
            $user = User::factory()->create([
                'name' => $operador->nombre.' '.$operador->apellido_paterno.' '.$operador->apellido_materno,
            ]); 

            $operador->id_user = $user->id;
            $operador->save();
        });
    }
}
