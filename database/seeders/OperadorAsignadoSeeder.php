<?php

namespace Database\Seeders;

use App\Models\OperadorAsignado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OperadorAsignadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OperadorAsignado::factory()->count(10)->create();

    }
}
