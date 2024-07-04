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
            ],
            [
                'nombre' => 'Medidro opaco',
                'descripcion' => 'Descripción de la anomalia 2',
            ],
        ]);
    }
}
