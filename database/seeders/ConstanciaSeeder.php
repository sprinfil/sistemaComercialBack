<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConstanciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('constancia_catalogos')->insert([
            [
                'nombre' => 'Constancia no adeudo',
                'descripcion' => 'Constancia que valida el no adeudo del usuario',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Constancia de contratacion reciente',
                'descripcion' => 'Constancia que valida la antigüedad de un contrato',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Constancia de antigüedad',
                'descripcion' => 'Constancia que valida la existancia de un contrato',
                'estado' => 'activo',
            ],
        ]);
    }
}
