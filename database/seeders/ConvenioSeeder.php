<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConvenioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       /* DB::table('convenio_catalogos')->insert([
            [
                'nombre' => 'Constancia no adeudo',
                'descripcion' => 'Constancia que valida el no adeudo del usuario',
            ],
            [
                'nombre' => 'Cosntancia de contratacion reciente',
                'descripcion' => 'Constancia que valida la antigüedad de un contrato',
            ],
            [
                'nombre' => 'Cosntancia de antigüedad',
                'descripcion' => 'Constancia que valida la existancia de un contrato',
            ],
        ]);
        */
    }
}
