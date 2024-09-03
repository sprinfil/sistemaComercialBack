<?php

namespace Database\Factories;

use App\Models\OrdenTrabajo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrdenTrabajo>
 */
class OrdenTrabajoFactory extends Factory
{
    protected $model = OrdenTrabajo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $estado_ot = $this->faker->randomElement(['No asignada', 'Concluida', 'En proceso', 'Cancelada']);
        $vigencia = $this->faker->optional()->date();
        $fecha_finalziada = null;
        $evidencia = null;//$this->faker->optional()->sentence;
        $observaciones = null;//$this->faker->optional()->sentence
        $material = null;//$this->faker->optional()->word
        if($estado_ot == 'Concluida'){
            $fecha = $this->faker->optional()->date();
            $fecha_finalziada = $fecha ? Carbon::parse($fecha)->subDay() : null;
            $evidencia = $this->faker->imageUrl(640, 480, 'cats', true, 'Faker', true);
            $observaciones = $this->faker->randomElement([
                'Ninguna', 
                'Estuvo fácil', 
                'Requiere revisión', 
                'Trabajo en equipo', 
                'Se cometieron errores', 
                'Excelente desempeño', 
                'Necesita más tiempo', 
                'Se utilizaron recursos adicionales'
            ]);
            
            $material = $this->faker->randomElement([
                'Ninguna', 
                'Material suficiente', 
                'Faltaron recursos', 
                'Equipo especializado necesario', 
                'Se reutilizó material', 
                'Material reciclado', 
                'Alta calidad', 
                'Material escaso'
            ]);
        }
        return [
            'id_toma' => \App\Models\Toma::pluck('id')->random(), // Obtiene un ID existente de 'tomas'
            'id_empleado_genero' => \App\Models\Operador::pluck('id')->random(), // Obtiene un ID existente de 'operadores' (empleados)
            'id_empleado_asigno' => \App\Models\Operador::pluck('id')->random(), // Obtiene un ID existente de 'operadores' o puede ser nulo
            'id_empleado_encargado' => $this->faker->optional()->randomElement(\App\Models\Operador::pluck('id')->toArray()), // Puede ser nulo o tener un ID válido de 'operadores'
            'id_orden_trabajo_catalogo' => \App\Models\OrdenTrabajoCatalogo::pluck('id')->random(), // Obtiene un ID existente de 'ot catalogo'
            'estado' => $estado_ot,//$this->faker->randomElement(['No asignada', 'Concluida', 'En proceso', 'Cancelada']),
            'fecha_finalizada' => $fecha_finalziada,
            'fecha_vigencia' => $vigencia,
            'obervaciones' => $observaciones,
            'evidencia' => $evidencia,
            'material_utilizado' => $material,
            'posicion_OT' => null,
            'genera_OT_encadenadas' => $this->faker->boolean,
        ];
    }
}
