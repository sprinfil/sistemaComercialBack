<?php

namespace Database\Seeders;

use App\Models\CargaTrabajo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CargaTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CargaTrabajo::factory()->count(30)->create();
    }
}
