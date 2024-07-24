<?php

namespace Database\Seeders;

use App\Models\Consumo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsumoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Consumo::factory()->count(30)->create();
    }
}
