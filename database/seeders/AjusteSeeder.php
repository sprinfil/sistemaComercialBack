<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AjusteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ajuste_catalogos')->insert([
            [
                'nombre' => 'Ajuste 1',
                'descripcion' => 'Descripción del Ajuste 1',
            ],
            [
                'nombre' => 'Ajuste 2',
                'descripcion' => 'Descripción del Ajuste 2',
            ],
        ]);
    }
}
