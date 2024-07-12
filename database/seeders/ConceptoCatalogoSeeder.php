<?php

namespace Database\Seeders;

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
        DB::table('concepto_catalogos')->insert([
            [
                'nombre' => 'Servicio de agua potable',
                'descripcion' => 'Aqui se describe el agua potable',
                'prioridad_abono' => '1',
            ],
            [
                'nombre' => 'Servicio de alcantarillado',
                'descripcion' => 'Aqui se describe el alcantarillado',
                'prioridad_abono' => '1',
            ],
            [
                'nombre' => 'Tratamiento y saneamiento',
                'descripcion' => 'Aqui se describe el tratamiento y el sanemianto',
                'prioridad_abono' => '1',
            ],
            [
                'nombre' => 'Multas',
                'descripcion' => 'Descripcion de la multa',
                'prioridad_abono' => '2',
            ],
            [
                'nombre' => 'Costo contrato de agua potable',
                'descripcion' => 'Descripcion del costo del contrato de agua',
                'prioridad_abono' => '1',
            ],
            [
                'nombre' => 'Costo contrato de alcantarillado',
                'descripcion' => 'Descripcion del costo del contrato de alcantarillado',
                'prioridad_abono' => '1',
            ],
            [
                'nombre' => 'Reconexion de agua',
                'descripcion' => 'Descripcion del costo de la reconexion',
                'prioridad_abono' => '3',
            ],
            [
                'nombre' => 'Constancia de factibilidad de agua',
                'descripcion' => 'Descripcion del costo de la factibilidad',
                'prioridad_abono' => '4',
            ],
            [
                'nombre' => 'Constancia de no servicio',
                'descripcion' => 'Descripcion de no servicio',
                'prioridad_abono' => '4',
            ],
            [
                'nombre' => 'Constancia de servicio',
                'descripcion' => 'Descripcion de no servicio',
                'prioridad_abono' => '5',
            ],
            [
                'nombre' => 'Profundidad',
                'descripcion' => 'Descripcion de la profundidad',
                'prioridad_abono' => '5',
            ],
        ]);
    }
}
