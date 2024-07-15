<?php

namespace Database\Seeders;

use App\Models\Toma;
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
        Toma::factory()->count(50)->create();
    }
}
