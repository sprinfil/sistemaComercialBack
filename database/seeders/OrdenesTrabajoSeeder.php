<?php

namespace Database\Seeders;

use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdenesTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*$conceptos = [
            "Reconexion de agua" => "Proceso de restablecimiento del suministro de agua potable a una propiedad que ha sido previamente desconectada. Este servicio puede ser necesario después de trabajos de mantenimiento, reparaciones o por falta de pago.",
            "Reconexion de drenaje" => "Proceso de restablecimiento de la conexión al sistema de alcantarillado de una propiedad que ha sido previamente desconectada. Este servicio es necesario después de reparaciones, trabajos de mantenimiento o problemas de pago.",
            "Cambio o rep. medidor" => "Sustitución del dispositivo que mide el consumo de agua en una propiedad. Este proceso puede realizarse por razones de mantenimiento, actualización del equipo o por mal funcionamiento del medidor existente.",
        ];
        
        foreach ($conceptos as $nombre => $descripcion) {
            $orden_trabajo = OrdenTrabajoCatalogo::factory()->create([
                'nombre' => $nombre,
            ]);
        } */
    }
}
