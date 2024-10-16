<?php

namespace Database\Factories;

use App\Models\Consumo;
use App\Models\Factura;
use App\Models\Lectura;
use App\Models\Libro;
use App\Models\Periodo;
use App\Models\Tarifa;
use App\Models\TarifaServiciosDetalle;
use App\Models\Toma;
use App\Services\Facturacion\FacturaService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Periodo>
 */
class PeriodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Periodo::class;

    public function definition(): array
    {
        // Si se proporcionan mes y ano como estado, úsalos; si no, genera valores aleatorios
        $mes = $this->faker->optional()->passthrough($this->state['mes'] ?? $this->faker->month);
        $ano = $this->faker->optional()->passthrough($this->state['ano'] ?? $this->faker->year);

        // Crear un objeto Carbon usando el mes y ano proporcionados o generados
        $periodo = Carbon::createFromDate($ano, $mes, 1);

        return [
            'id_ruta' => Libro::all()->random()->id,
            'id_tarifa' => Tarifa::all()->random()->id,
            'nombre' => $periodo->translatedFormat('F Y'),  // Nombre en formato "Mes Ano"
            'periodo' => $periodo->format('m-Y'),  // Periodo en formato "MM-YYYY"
            'facturacion_fecha_inicio' => $periodo->startOfMonth(),  // Inicio de mes del periodo
            'facturacion_fecha_final' => $periodo->endOfMonth(),  // Fin de mes del periodo
            'lectura_inicio' => $periodo->startOfMonth(),  // Inicio de mes del periodo
            'lectura_final' => $periodo->endOfMonth()->addDays(30),  // Fin de mes + 30 días del periodo
            'validacion_inicio' => $periodo->startOfMonth(),  // Inicio de mes del periodo
            'validacion_final' => $periodo->endOfMonth()->addDays(30),  // Fin de mes + 30 días del periodo
            'recibo_inicio' => $periodo->startOfMonth(),  // Inicio de mes del periodo
            'recibo_final' => $periodo->endOfMonth()->addDays(30),  // Fin de mes + 30 días del periodo
        ];
    }

    public function conMesYAno($mes, $ano)
    {
        return $this->state(function (array $attributes) use ($mes, $ano) {
            // Usar mes y ano para generar datos, pero no almacenarlos directamente
            $periodo = Carbon::createFromDate($ano, $mes, 1);

            return [
                'nombre' => $periodo->translatedFormat('F Y'),
                'periodo' => $periodo->format('Y-m-d'),
                'facturacion_fecha_inicio' => $periodo->startOfMonth(),
                'facturacion_fecha_final' => $periodo->endOfMonth(),
                'lectura_inicio' => $periodo->startOfMonth(),
                'lectura_final' => $periodo->endOfMonth(),
                'validacion_inicio' => $periodo->startOfMonth(),  // Inicio de mes del periodo
                'validacion_final' => $periodo->endOfMonth()->addDays(30),  // Fin de mes + 30 días del periodo
                'recibo_inicio' => $periodo->startOfMonth(),  // Inicio de mes del periodo
                'recibo_final' => $periodo->endOfMonth()->addDays(30),  // Fin de mes + 30 días del periodo
                'vigencia_recibo' => $periodo->endOfMonth()->addDays(30),  // Fin de mes + 30 días del periodo
            ];
        });
    }

    /**
     * Configure the factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Periodo $periodo) {
            $tomas = Toma::whereNotNull('c_agua')->get();
            /* Cambiado por consumo
            foreach ($tomas as $toma) {
                Factura::factory()->create([
                    'id_periodo' => $periodo->id,
                    'id_toma' => $toma->id,
                    'id_consumo' => 0, //Consumo::all()->random()->id,
                    'id_tarifa_servicio' => $periodo->id_tarifa,
                    'monto' => $this->faker->numberBetween(1, 400),
                    'fecha' => $periodo->periodo,
                ]);
            }
                */
                foreach ($tomas as $toma) {
                    if ($toma->estatus=="limitado" || $toma->estatus=="activa"  )
                    if ($toma->tipo_servicio=="lectura"){
                        $lectura=Lectura::create([
                            "id_operador"=>1,
                            "id_toma"=>$toma->id,
                            "id_periodo"=>$periodo->id,
                            "lectura"=>$this->faker->randomNumber(2),
                            ]);
                           Consumo::create([
                            "id_toma"=>$toma->id,
                            "id_periodo"=>$periodo->id,
                            "id_lectura_actual"=>$lectura->id,
                            "tipo"=>"lectura",
                            "estado"=>"activo",
                            "consumo"=>$lectura->lectura
                           ]);
                    }
                    else{
                        Consumo::create([
                            "id_toma"=>$toma->id,
                            "id_periodo"=>$periodo->id,
                            "tipo"=>"promedio",
                            "estado"=>"activo",
                            "consumo"=>$this->faker->randomNumber(2)
                           ]);
                    }
                 
                } 
        });
    }
}
