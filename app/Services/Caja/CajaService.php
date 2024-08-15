<?php
namespace App\Services\Caja;

use App\Models\Caja;
use App\Models\OperadorAsignado;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CajaService{

   public function iniciarCaja(int $idOperador, int $idCaja, array $caja, array $fondo)
   {
     try {
        $cajaAsignada = OperadorAsignado::where('id:caja',$idCaja);
        
     } catch (\Throwable $th) {
        
     }
   }
}