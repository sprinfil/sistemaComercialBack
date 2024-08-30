<?php

namespace Database\Seeders;

use App\Models\TarifaServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TarifaServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TarifaServicio::factory()->count(12)->create();

    }
}
