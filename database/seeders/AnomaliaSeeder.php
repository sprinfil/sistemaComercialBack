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
        /*DB::table('anomalia_catalogos')->insert([
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
        ]);*/
        $anomalias = [
            ['nombre' => 'Perro Bravo', 'descripcion' => 'Presencia de un perro agresivo que impide el acceso al medidor.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'No Hay Medidor', 'descripcion' => 'El medidor de agua no está instalado o ha sido retirado.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'Medidor Opaco', 'descripcion' => 'El medidor está sucio u opaco, lo que impide la lectura correcta.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'Alto Consumo Anómalo', 'descripcion' => 'Se detecta un consumo de agua anormalmente alto que puede indicar una fuga o uso indebido.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'Bajo Consumo Anómalo', 'descripcion' => 'Se detecta un consumo de agua anormalmente bajo que puede indicar un medidor defectuoso o manipulado.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'Acceso Restringido', 'descripcion' => 'No se puede acceder al medidor de agua debido a barreras físicas o restricciones de propiedad.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'Medidor Dañado', 'descripcion' => 'El medidor de agua está dañado y no proporciona lecturas precisas.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'Medidor Ausente', 'descripcion' => 'El medidor de agua no está presente en la ubicación esperada.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'Medidor Bloqueado', 'descripcion' => 'El medidor de agua está bloqueado por objetos o vegetación.', 'estado' => 'activo', 'facturable' => false],
            ['nombre' => 'Lectura Imposible', 'descripcion' => 'Por diversas razones, no es posible obtener una lectura del medidor.', 'estado' => 'activo', 'facturable' => false],
        ];
    }
}
