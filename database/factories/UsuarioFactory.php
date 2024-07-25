<?php

namespace Database\Factories;

use App\Models\DatoFiscal;
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
        static $count = 0;
        $codigo = date('Ym', (time())).($count++);
        return [
            'codigo_usuario' => intval($codigo),
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

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Usuario $usuario) {
            DatoFiscal::factory()->create([
                'id_modelo' => $usuario->id,
                'modelo' => 'usuario',
                'regimen_fiscal' => $this->faker->randomElement([
                    'Regimen Simplificado de Confianza',
                    'Sueldos y salarios e ingresos asimilados a salarios',
                    'Regimen de Actividades Empresariales y Profesionales',
                    'Regimen de Incorporacion Fiscal',
                    'Enajenacion de bienes',
                    'Regimen de Actividades Empresariales con ingresos a traves de Plataformas Tecnologicas',
                    'Regimen de Arrendamiento',
                    'Intereses',
                    'Obtencion de premios',
                    'Dividendos',
                    'Demas ingresos'
                ]),
                'correo' => $usuario->correo,
                'razon_social' => $usuario->rfc,
                'telefono' => $usuario->telefono,
                'pais' => 'México',
                'estado' => 'Baja California Sur',
                'municipio' => $this->faker->city,
                'localidad' => $this->faker->city,
                'colonia' => $this->faker->streetName,
                'calle' => $this->faker->streetAddress,
                'referencia' => 'ninguna',
                'numero_exterior' => $this->faker->numerify('###'),
                'codigo_postal' => $this->faker->postcode
            ]);
        });
    }
}
