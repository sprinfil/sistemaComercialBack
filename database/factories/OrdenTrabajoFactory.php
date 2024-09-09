<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoCatalogo;
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
    $vigencia = $this->faker->date();
    $fecha_finalizada = null;
    $fecha_asignacion = null;
    $evidencia = null;
    $observaciones = null;
    $material = null;

    if ($estado_ot != 'No asignada' && $estado_ot != 'Cancelada') {
        $fecha_asignacion = Carbon::parse($vigencia)->subDays(2);
    }

    if ($estado_ot == 'Concluida') {
        $vigencia = $vigencia ?? Carbon::now();
        $fecha_finalizada = Carbon::parse($vigencia)->subDay();
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
       'id_toma' => \App\Models\Toma::where('id', '>', 20)->pluck('id')->random(),
        'id_empleado_genero' => \App\Models\Operador::pluck('id')->random(),
        'id_empleado_asigno' => in_array($estado_ot, ['Concluida', 'En proceso']) 
            ? \App\Models\Operador::pluck('id')->random() 
            : null, // No asignar operador si está "No asignada" o "Cancelada"
        'id_empleado_encargado' => in_array($estado_ot, ['Concluida', 'En proceso']) 
            ? \App\Models\Operador::pluck('id')->random() 
            : null, // No asignar operador si está "No asignada" o "Cancelada"
        'id_orden_trabajo_catalogo' => \App\Models\OrdenTrabajoCatalogo::pluck('id')->random(),
        'estado' => $estado_ot,
        'fecha_asignacion' => $fecha_asignacion,
        'fecha_finalizada' => $fecha_finalizada,
        'fecha_vigencia' => $vigencia,
        'obervaciones' => $observaciones,
        'evidencia' => $evidencia,
        'material_utilizado' => $material,
        'posicion_OT' => null,
        'genera_OT_encadenadas' => $this->faker->boolean,
    ];
}

/**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (OrdenTrabajo $ot) {
            if($ot->nombre == 'Limitación')
            {
                $ot_cat = OrdenTrabajoCatalogo::find($ot->id_orden_trabajo_catalogo);
                Cargo::factory()->create([
                    'id_concepto' => 146,
                    'nombre' => $ot_cat->nombre,
                    'id_origen' => $ot_cat->id,
                    'modelo_origen' => 'orden_trabajo',
                    'id_dueno' => $ot->id_toma,
                    'modelo_dueno' => 'toma',
                    'monto' => 400,
                    'iva' => (0.16 * 400),
                    'estado' => 'pendiente',
                    'fecha_cargo' => Carbon::now(),
                    'fecha_liquidacion' => null, //$fecha_liquidacion,
                    'deleted_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
        });
    }
     

}
