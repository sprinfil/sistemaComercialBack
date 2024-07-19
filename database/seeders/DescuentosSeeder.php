<?php

namespace Database\Seeders;

use App\Models\DescuentoAsociado;
use App\Models\DescuentoCatalogo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DescuentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $descuentos = [
            "Descuento de Verano" => "20% de descuento en el servicio de agua durante la temporada de verano y 100% de descuento en el recargo.",
            "Descuento de Invierno" => "15% de descuento en el servicio de alcantarillado durante la temporada de invierno y 100% de descuento en el recargo.",
            "Descuento de Bienvenida" => "10% de descuento en la primera factura de servicios de agua y saneamiento para nuevos clientes, más 100% de descuento en el recargo.",
            "Descuento de Lealtad" => "5% de descuento adicional en el servicio de agua para clientes recurrentes.",
            "Descuento de Cumpleaños" => "25% de descuento en una factura de servicio de alcantarillado durante el mes de tu cumpleaños y 100% de descuento en el recargo.",
            "Descuento Estudiantil" => "30% de descuento en el servicio de agua para estudiantes con identificación válida.",
            "Descuento Militar" => "20% de descuento en el servicio de saneamiento para personal militar activo y veteranos, y 100% de descuento en el recargo.",
            "Descuento para Mayores" => "15% de descuento en servicios de agua y alcantarillado para personas mayores de 65 años.",
            "Descuento por Referencia" => "10% de descuento en el servicio de agua cuando refieres a un amigo que se suscribe.",
            "Descuento por Volumen" => "Descuento variable en el servicio de agua basado en el volumen de consumo.",
            "Descuento de Fin de Semana" => "15% de descuento en el servicio de alcantarillado durante los fines de semana.",
            "Descuento de Liquidación" => "50% de descuento en tarifas de servicios de saneamiento seleccionadas en liquidación.",
            "Descuento de Membresía" => "20% de descuento en el servicio de agua para miembros con suscripción activa.",
            "Descuento por Pago en Efectivo" => "5% de descuento en servicios de agua pagados en efectivo.",
            "Descuento de Temporada" => "Descuento variable en servicios de alcantarillado y saneamiento basado en la temporada del año y 100% de descuento en el recargo."
        ];              
        
        foreach ($descuentos as $nombre => $descripcion) {
            DescuentoCatalogo::factory()->create([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'estado' => 'activo',
            ]);
        }

        DescuentoAsociado::factory()->count(10)->create();
    }
}
