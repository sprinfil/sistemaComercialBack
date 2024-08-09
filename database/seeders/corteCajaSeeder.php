<?php

namespace Database\Seeders;

use App\Models\CorteCaja;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class corteCajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        corteCaja::factory()->count(10)->create();
    }
}
