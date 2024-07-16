<?php

namespace Database\Seeders;

use App\Models\Contrato;
use App\Models\Toma;
use App\Models\Medidor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea 50 registros de tomas utilizando la fábrica
        Toma::factory()->count(10)->create();
        // Crea 50 registros de medidores 
        Medidor::factory()->count(10)->create();
        // Crea 50 registros de contrato
        Contrato::factory()->count(10)->create();
    }
}
