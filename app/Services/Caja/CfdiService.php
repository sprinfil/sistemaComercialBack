<?php

namespace App\Services\Caja;

use App\Models\Cfdi;
use App\Models\DatoFiscal;
use App\Models\Pago;
use Exception;
use Faker\Factory as FakerFactory;
use Illuminate\Support\Facades\DB;

class CfdiService
{
  public function timbrarPago($data)
  {
    try {
      DB::beginTransaction();
      // Lista de posibles estados
      $estados = ['pendiente', 'fallido', 'realizado', 'cancelado'];

      // Seleccionar un estado aleatoriamente
      $data['estado'] = 'realizado'; //$estados[array_rand($estados)];

      $pago = Pago::where('folio', $data['folio'])->first();
      $datos_fiscales = $pago->dueno->datos_fiscales;
      if (!$datos_fiscales) {
        $datos_fiscales = $pago->duenoUsuario->datos_fiscales;
      }
      $res = DatoFiscal::findOrFail($datos_fiscales->id ?? 0);
      $data['id_datos_fiscales'] = $datos_fiscales->id;

      // Instanciar Faker para generar la imagen
      $faker = FakerFactory::create();

      // Si el estado es 'realizado' o 'cancelado', generar y guardar la imagen
      if ($data['estado'] === 'realizado' || $data['estado'] === 'cancelado') {
        $data['documento'] = $faker->imageUrl(640, 480, 'cats', true, 'Faker', true);
      }

      // Crear el registro en la base de datos
      $cfdi = Cfdi::create($data);
      DB::commit();
      return $cfdi;
    } catch (Exception $e) {
      DB::rollBack();
      throw $e;
    }
  }
}
