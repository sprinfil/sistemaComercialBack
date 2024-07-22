<?php

namespace Database\Seeders;

use App\Models\AjusteCatalogo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AjusteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ajustes = [
            "Ajuste de Error en Facturación de Verano" => "20% de ajuste en el servicio de agua debido a un error en la facturación durante la temporada de verano y 100% de ajuste en el recargo.",
            "Ajuste de Error en Facturación de Invierno" => "15% de ajuste en el servicio de alcantarillado debido a un error en la facturación durante la temporada de invierno y 100% de ajuste en el recargo.",
            "Ajuste de Descuento de Bienvenida No Aplicado" => "10% de ajuste en la primera factura de servicios de agua y saneamiento para nuevos clientes, más 100% de ajuste en el recargo debido a un descuento de bienvenida no aplicado.",
            "Ajuste de Error de Facturación por Lealtad" => "5% de ajuste adicional en el servicio de agua para clientes recurrentes debido a un error en la facturación.",
            "Ajuste de Error de Facturación de Cumpleaños" => "25% de ajuste en una factura de servicio de alcantarillado durante el mes de tu cumpleaños y 100% de ajuste en el recargo debido a un error en la aplicación del descuento de cumpleaños.",
            "Ajuste de Descuento Estudiantil No Aplicado" => "30% de ajuste en el servicio de agua para estudiantes con identificación válida debido a un descuento no aplicado.",
            "Ajuste de Error en Facturación Militar" => "20% de ajuste en el servicio de saneamiento para personal militar activo y veteranos, y 100% de ajuste en el recargo debido a un error en la facturación.",
            "Ajuste de Error de Facturación para Mayores" => "15% de ajuste en servicios de agua y alcantarillado para personas mayores de 65 años debido a un error en la facturación.",
            "Ajuste de Descuento por Referencia No Aplicado" => "10% de ajuste en el servicio de agua cuando refieres a un amigo que se suscribe debido a un descuento no aplicado.",
            "Ajuste de Error en Facturación por Volumen" => "Ajuste variable en el servicio de agua basado en el volumen de consumo debido a un error en la facturación.",
            "Ajuste de Error en Facturación de Fin de Semana" => "15% de ajuste en el servicio de alcantarillado durante los fines de semana debido a un error en la facturación.",
            "Ajuste de Error en Liquidación" => "50% de ajuste en tarifas de servicios de saneamiento seleccionadas en liquidación debido a un error en la facturación.",
            "Ajuste de Error en Facturación de Membresía" => "20% de ajuste en el servicio de agua para miembros con suscripción activa debido a un error en la facturación.",
            "Ajuste de Error en Facturación por Pago en Efectivo" => "5% de ajuste en servicios de agua pagados en efectivo debido a un error en la facturación.",
            "Ajuste de Error de Temporada" => "Ajuste variable en servicios de alcantarillado y saneamiento basado en la temporada del año y 100% de ajuste en el recargo debido a un error en la facturación."
        ];
        
        foreach ($ajustes as $nombre => $descripcion) {
            AjusteCatalogo::factory()->create([
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'estado' => 'activo',
            ]);
        }
    }
}
