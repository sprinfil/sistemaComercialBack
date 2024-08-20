<?php
namespace App\Services\Caja;

use App\Http\Resources\CajaResource;
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
        //Obtencion de datos del arreglo
        $idOperador = $data['id_operador'];
        $idCajaCatalogo = $data['id_caja_catalogo'];
        $horaApertura = $data['fecha_apertura'];

        //formateo de la hora de apertura a horas-minutos-segundos
        $horaApertura = Carbon::parse($horaApertura);
        $horaApertura = $horaApertura->format('H:i:s');
        

        //Consulta para obtener si el operador esta asignado a la caja en la que se desea registrar
        $cajaAsignada = OperadorAsignado::where('id_caja_catalogo',$idCajaCatalogo)->where('id_operador',$idOperador)->first();

        //Obtencion de la informacion de la caja en la que se va a registrar
        $CajaCatalogo = CajaCatalogo::FindOrFail($cajaAsignada->id_caja_catalogo);

        //Obtencion de un registro previo en caso de existir uno en el mismo dia y que no se haya cerrado el corte
        $cajaPreviaReg = Caja::where('id_caja_catalogo',$idCajaCatalogo)
        ->where('id_operador',$idOperador)
        ->whereDate('fecha_apertura', Carbon::today())
        ->where('fecha_cierre', null)
        ->get();

            //El id_operador ingresado debe ser igual al de la caja en la que se quiere logear
        if($cajaAsignada->id_operador == $idOperador)
        {
            //Se debe estar intentando ingresar entre las horas autorizadas en la caja del catalogo de cajas
          if($horaApertura >= $CajaCatalogo->hora_apertura && $horaApertura <= $CajaCatalogo->hora_cierre){

            //No debe existir un registro previo de la caja en el mismo dia a menos que este registro ya cuente con una fecha de cierre
           if (count($cajaPreviaReg) == 0) {

                $caja = Caja::create($data);
                return response(new CajaResource($caja));;
           }
           //condicion en caso de que la caja ya cuente con un registro abierto el dia actual
           else{
            return response()->json([
              'error' => 'La caja ya se encuentra abierta.'
          ]);
           }
           
          }
          //Condicion si el operador no esta intentanto abrir la caja en horario de cobro
          else{
            return response()->json([
              'error' => 'La caja no puede abrir fuera del horario de cobro.'
          ]);
          }
            
        }
        //condicion si el operador no esta asignado a la caja
        else{
          return response()->json([
            'error' => 'El operador no se encuentra asignado a esta caja.'
        ]);
        }
        
        
     } catch (Exception $ex) {
        return response()->json([
            'error' => 'Ocurrio un error durante la apertura.'
        ]);
     }
   }

   public function corteCaja(array $data)
   {//aqui estoy
      try {
        $cajaData = $data['caja_data'][0];
        $corteData = $data['corte_data'][0];

        //$corteDataArr = json_decode($cajaData, true);
        //var_dump($cajaData);
        //return var_dump($cajaData);;
        //Consulta de registro de caja por idOperador,idCajaCatalogo donde el registro sea del dia actual y no cuente con fecha de cierre
        $cajaHisto = Caja::where('id_operador',$data['caja_data'][0]['id_operador'])
        ->where('id_caja_catalogo',$data['caja_data'][0]['id_caja_catalogo'])
        ->whereDate('fecha_apertura',Carbon::today())
        ->where('fecha_cierre',null)
        ->first();

        $corteReg = [$cajaHisto->id, $data['caja_data'][0]['id_operador'],$data['corte_data'][0]['estatus']]; 

        return $corteData;



        if ($data['caja_data'][0]['id_operador'] == $data['corte_data'][0]['id_operador'] ) {

          //$cajaHisto->update($data['caja_data'][0]);
        }
        else{

        }
       
      } catch (Exception $ex) {
        return response()->json([
          'error' => 'Ocurrio un error al realizar el cierre de caja.'
      ]);
      }
     
   }
}