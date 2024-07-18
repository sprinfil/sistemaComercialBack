<?php

namespace Database\Seeders;

use App\Models\ConceptoCatalogo;
use App\Models\TarifaServiciosDetalle;
use App\Models\TipoToma;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConceptoCatalogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        DB::table('concepto_catalogos')->insert([
            [
                'nombre' => 'Servicio de agua potable',
                'descripcion' => 'Aqui se describe el agua potable',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Servicio de alcantarillado',
                'descripcion' => 'Aqui se describe el alcantarillado',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Tratamiento y saneamiento',
                'descripcion' => 'Aqui se describe el tratamiento y el sanemianto',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Multas',
                'descripcion' => 'Descripcion de la multa',
                'prioridad_abono' => '2',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Costo contrato de agua potable',
                'descripcion' => 'Descripcion del costo del contrato de agua',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Costo contrato de alcantarillado',
                'descripcion' => 'Descripcion del costo del contrato de alcantarillado',
                'prioridad_abono' => '1',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Reconexion de agua',
                'descripcion' => 'Descripcion del costo de la reconexion',
                'prioridad_abono' => '3',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Constancia de factibilidad de agua',
                'descripcion' => 'Descripcion del costo de la factibilidad',
                'prioridad_abono' => '4',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Constancia de no servicio',
                'descripcion' => 'Descripcion de no servicio',
                'prioridad_abono' => '4',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Constancia de servicio',
                'descripcion' => 'Descripcion de no servicio',
                'prioridad_abono' => '5',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Profundidad',
                'descripcion' => 'Descripcion de la profundidad',
                'prioridad_abono' => '5',
                'estado' => 'activo',
            ],
        ]);*/

        DB::table('tarifas')->insert([
            [
                'nombre' => 'TARIFA JULIO 2024',
                'descripcion' => 'ninguna',
                'fecha' => now(),
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $tipo_tomas = TipoToma::all();

        $tipo_tomas->each(function ($tipo_toma) {
            $rango = 17;
            for ($i = 1; $i <= 10; $i++) {
                TarifaServiciosDetalle::factory()->create([
                    'id_tarifa'=>1,
                    'id_tipo_toma'=>$tipo_toma->id,
                    'rango'=>$rango*$i,
                    'agua'=>10*17,
                    'alcantarillado'=>2*17,
                    'saneamiento'=>2*17
                ]);
            }
        });

        ConceptoCatalogo::factory()->count(10)->create();
    }
}
