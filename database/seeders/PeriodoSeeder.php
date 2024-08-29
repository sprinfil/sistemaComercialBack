<?php

namespace Database\Seeders;

use App\Models\Periodo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Periodo::factory()->count(30)->create();
        $mesInicial = 1; // Enero
        $anoInicial = 2024;

        for ($i = 0; $i < 4; $i++) {
            $mes = ($mesInicial + $i) % 12 ?: 12; // Asegura que el mes esté entre 1 y 12
            $ano = $anoInicial + floor(($mesInicial + $i - 1) / 12); // Incrementa el ano cuando se pasa de diciembre

            Periodo::factory()->conMesYAno($mes, $ano)->create();
        }
    }
}
