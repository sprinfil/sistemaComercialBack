<?php
namespace App\Services\Caja;

use App\Models\Caja;
use App\Models\CajaCatalogo;
use App\Models\OperadorAsignado;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CajaService{

   public function iniciarCaja(array $data)
   {
     try {
        $idOperador = $data['id_operador'];
        $idCajaCatalogo = $data['id_caja'];
        $caja = $data['caja_data'];
        
        $fechaApertura = $caja[0]['fecha_apertura'];
        $fechaApertura = Carbon::parse($fechaApertura);
        $fechaApertura = $fechaApertura->format('H:i:s');

        $cajaAsignada = OperadorAsignado::where('id_caja_catalogo',$idCajaCatalogo)->first();
        $CajaCatalogo = CajaCatalogo::FindOrFail($cajaAsignada->id_caja_catalogo);
       // return $CajaCatalogo;
        //return $cajaAsignada;

            //el id operador ingresado debe ser igual al de la caja en la que se quiere logear
        if($cajaAsignada->id_operador == $idOperador)
        {
            //se debe estar intentando ingresar entre las horas autorizadas en la caja del catalogo de cajas
          if($fechaApertura>= $CajaCatalogo->hora_apertura && $fechaApertura<=$CajaCatalogo->hora_cierre){
            return response()->json([
               'Jalo.'
             ]);
          }
            
        }
        
        
     } catch (\Throwable $th) {
        
     }
   }
}