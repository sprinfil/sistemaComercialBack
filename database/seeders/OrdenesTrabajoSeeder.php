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
        $conceptos = [
            "reconexion de agua" => "Proceso de restablecimiento del suministro de agua potable a una propiedad que ha sido previamente desconectada. Este servicio puede ser necesario después de trabajos de mantenimiento, reparaciones o por falta de pago.",
            "reconexion de alcantarillado" => "Proceso de restablecimiento de la conexión al sistema de alcantarillado de una propiedad que ha sido previamente desconectada. Este servicio es necesario después de reparaciones, trabajos de mantenimiento o problemas de pago.",
            "recuperación de medidor" => "Sustitución del dispositivo que mide el consumo de agua en una propiedad. Este proceso puede realizarse por razones de mantenimiento, actualización del equipo o por mal funcionamiento del medidor existente.",
            "recuperación de lectura" => "Proceso de obtención de la lectura actual del medidor de agua cuando no se ha podido realizar en el tiempo programado. Esto puede incluir visitas adicionales para asegurar que se registre el consumo correcto de agua."
        ];
        
        foreach ($conceptos as $nombre => $descripcion) {
            $orden_trabajo = OrdenTrabajoCatalogo::factory()->create([
                'nombre' => $nombre,
            ]);
        } 
    }
}
