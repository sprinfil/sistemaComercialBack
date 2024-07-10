<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonificacionCatalogoSeeder extends Seeder
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
          
        ]);
        //
    }
}
