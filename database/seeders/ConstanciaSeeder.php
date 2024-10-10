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
                'id_concepto_catalogo' => '1',
                'nombre' => 'Constancia no adeudo',
                'descripcion' => 'Constancia que valida el no adeudo del usuario',
                'estado' => 'activo',
            ],
            [
                'id_concepto_catalogo' => '2',
                'nombre' => 'Constancia de contratacion reciente',
                'descripcion' => 'Constancia que valida la antigüedad de un contrato',
                'estado' => 'activo',
            ],
            [
                'id_concepto_catalogo' => '3',
                'nombre' => 'Constancia de antigüedad',
                'descripcion' => 'Constancia que valida la existancia de un contrato',
                'estado' => 'activo',
            ],
            [
                'id_concepto_catalogo' => '4',
                'nombre' => 'Constancia de no servicio',
                'descripcion' => 'Constancia que valida la ausencia de servicio del usuario',
                'estado' => 'activo',
            ],
        ]);
    }
}
