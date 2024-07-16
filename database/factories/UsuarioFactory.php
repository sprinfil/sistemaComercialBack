<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellido_paterno' => $this->faker->lastName,
            'apellido_materno' => $this->faker->lastName,
            'nombre_contacto' => $this->faker->name,
            'telefono' => $this->faker->numerify('##########'),
            'curp' => strtoupper($this->faker->bothify('????######??????##')),
            'rfc' => strtoupper($this->faker->bothify('????######???')),
            'correo' => $this->faker->unique()->safeEmail,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
