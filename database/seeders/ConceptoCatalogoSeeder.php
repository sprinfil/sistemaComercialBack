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
            ],
            [
                'nombre' => 'Servicio de alcantarillado',
                'descripcion' => 'Aqui se describe el alcantarillado',
            ],
            [
                'nombre' => 'Tratamiento y saneamiento',
                'descripcion' => 'Aqui se describe el tratamiento y el sanemianto',
            ],
            [
                'nombre' => 'Multas',
                'descripcion' => 'Descripcion de la multa',
            ],
            [
                'nombre' => 'Costo contrato de agua potable',
                'descripcion' => 'Descripcion del costo del contrato de agua',
            ],
            [
                'nombre' => 'Costo contrato de alcantarillado',
                'descripcion' => 'Descripcion del costo del contrato de alcantarillado',
            ],
            [
                'nombre' => 'Reconexion de agua',
                'descripcion' => 'Descripcion del costo de la reconexion',
            ],
            [
                'nombre' => 'Constancia de factibilidad de agua',
                'descripcion' => 'Descripcion del costo de la factibilidad',
            ],
            [
                'nombre' => 'Constancia de no servicio',
                'descripcion' => 'Descripcion de no servicio',
            ],
            [
                'nombre' => 'Profundidad',
                'descripcion' => 'Descripcion de la profundidad',
            ],
        ]);
    }
}
