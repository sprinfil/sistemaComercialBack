<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnomaliaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('anomalia_catalogos')->insert([
            [
                'nombre' => 'Sin medidor',
                'descripcion' => 'Descripción de la anomalia 1',
                'estado' => 'activo',
                'facturable' => 0
            ],
            [
                'nombre' => 'Medidor opaco',
                'descripcion' => 'Descripción de la anomalia 2',
                'estado' => 'activo',
                'facturable' => 0
            ],
        ]);
    }
}
