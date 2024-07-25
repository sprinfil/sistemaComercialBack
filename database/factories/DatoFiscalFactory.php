<?php

namespace Database\Factories;

use App\Models\DatoFiscal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DatoFiscal>
 */
class DatoFiscalFactory extends Factory
{
    protected $model = DatoFiscal::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_modelo' => 0,
            'modelo' => '',
            'regimen_fiscal' => '',
            'nombre' => '',
            'correo' => '',
            'razon_social' => '',
            'telefono' => '',
            'pais' => '',
            'estado' => '',
            'municipio' => '',
            'localidad' => '',
            'colonia' => '',
            'calle' => '',
            'referencia' => '',
            'numero_exterior' => '',
            'codigo_postal' => ''
        ];
    }
}
