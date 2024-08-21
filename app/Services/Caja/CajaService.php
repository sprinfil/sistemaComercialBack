<?php
namespace App\Services\Caja;

use App\Http\Resources\CajaResource;
use App\Http\Resources\CorteCajaResource;
use App\Http\Resources\PagoResource;
use App\Models\Caja;
use App\Models\CajaCatalogo;
use App\Models\CorteCaja;
use App\Models\OperadorAsignado;
use App\Models\Pago;
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
   {
    //var_dump($cajaData);
      try {

        $discrepancia = "no";
        $discrepanciaMonto = 0;
        

        //Valida que la suma de los totales coincida con el total general
        if (($data['corte_data'][0]['total_efectivo_real'] + 
             $data['corte_data'][0]['total_tarjetas_real'] + 
             $data['corte_data'][0]['total_cheques_real']) != $data['corte_data'][0]['total_real'] )
        {
             return response()->json([
            'error' => 'La suma de los totales no coincide con el total real.'
             ]);
        }
        
        //Consulta de registro de caja por idOperador,idCajaCatalogo donde el registro sea del dia actual y no cuente con fecha de cierre
        $cajaHisto = Caja::where('id_operador',$data['caja_data'][0]['id_operador'])
        ->where('id_caja_catalogo',$data['caja_data'][0]['id_caja_catalogo'])
        ->whereDate('fecha_apertura',Carbon::today())
        ->where('fecha_cierre',null)
        ->first();
        
        //Verifica que exista un registro caja el cual finalizar 
        if ($cajaHisto) {
          //Obtiene el total de dinero registrado en los pagos
          $totalRegistrado = $cajaHisto->totalPorTipo("efectivo") + $cajaHisto->totalPorTipo("tarjeta") + $cajaHisto->totalPorTipo("cheque");
          
          //Verifica diferencias en el total de los registros y el total enviado por el cajero
          if ($totalRegistrado != $data['corte_data'][0]['total_real']) {
            $discrepancia = "si";
            $discrepanciaMonto = abs($totalRegistrado - $data['corte_data'][0]['total_real']);
          }
                
          //Crea el registro de corte de caja
          $corteReg = [
            "id_caja" => $cajaHisto->id, 
            "id_operador" =>$data['caja_data'][0]['id_operador'],
            "estatus" => "pendiente",
            "total_efectivo_registrado" =>  $cajaHisto->totalPorTipo("efectivo"),
            "total_efectivo_real" => $data['corte_data'][0]['total_efectivo_real'],
            "total_tarjetas_registrado" => $cajaHisto->totalPorTipo("tarjeta"),
            "total_tarjetas_real" =>$data['corte_data'][0]['total_tarjetas_real'],
            "total_cheques_registrado" => $cajaHisto->totalPorTipo("cheque"),
            "total_cheques_real" =>$data['corte_data'][0]['total_cheques_real'],
            "total_registrado" => $totalRegistrado,
            "total_real" => $data['corte_data'][0]['total_real'],
            "discrepancia" => $discrepancia,
            "discrepancia_monto" => $discrepanciaMonto,
            "fecha_corte" => $data['corte_data'][0]['fecha_corte']
          ]; 
          //return  $corteReg;
          
          //El operador debe ser igual en el registro de caja y en el corte
          if ($data['caja_data'][0]['id_operador'] == $data['corte_data'][0]['id_operador'] ) {
            //Registra el corte y actualiza el cierre de caja
             $cajaHisto->update($data['caja_data'][0]);
             $corte = CorteCaja::create($corteReg);
             $corte->save();
             //Mensaje de exito
             return response()->json([
              'Se ha finalizado el cierre de caja y se registro el corte.'
             ]);
          }
          else{
            return response()->json([
              'error' => 'El operador no en la caja y en el corte no coincide.'
             ]);
          }
        }else{
          return response()->json([
            'error' => 'No existen cajas abiertas.'
           ]);
        }
  
      } catch (Exception $ex) {
        return response()->json([
          'error' => 'Ocurrio un error al realizar el cierre de caja.'.$ex
      ]);
      }
     
   }

   public function asignaciorOperador(array $data)
   {
    
   }

}