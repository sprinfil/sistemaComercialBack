<?php
namespace App\Services\Caja;

use App\Http\Resources\CajaCatalogoResource;
use App\Http\Resources\CajaResource;
use App\Http\Resources\CorteCajaResource;
use App\Http\Resources\OperadorAsignadoResource;
use App\Http\Resources\PagoResource;
use App\Models\Caja;
use App\Models\CajaCatalogo;
use App\Models\CorteCaja;
use App\Models\OperadorAsignado;
use App\Models\Pago;
use App\Models\SolicitudCancelacionPago;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
              'error' => 'El operador en la caja y en el corte no coincide.'
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


  public function pagosPorCaja(Request $request)
  {
    try{
      $data = $request->all();
      $id_caja = $data['id_caja'];
      $pagos = Caja::findOrFail($id_caja)->pagos;
      return $pagos;
    } catch(Exception $ex){
      throw $ex;
    }
  }

  public function cargoPorCaja(Request $request)
  {
    try{
      $data = $request->all();
      $id_caja = $data['id_caja'];
      $cargos = Caja::findOrFail($id_caja)->cargos;
      return $cargos;
    } catch(Exception $ex){
      throw $ex;
    }
  }

  public function solicitudCancelacionPago(Request $request)
  {  
    try{
      $data = $request->all();
      return SolicitudCancelacionPago::create($data);
    } catch(Exception $ex){
      throw $ex;
    }
  }

   public function asignarOperadorService(array $data)
   {
    $operadores=new Collection();
    $operadorer_id=[];
        foreach ($data as $operador){

          //Consulta si el operador esta o estuvo asignado a la caja
          /*
        $operadorRepetido = OperadorAsignado::withTrashed()
        ->where('id_caja_catalogo',$operador['id_caja_catalogo'])
        ->where('id_operador',$operador['id_operador'])
        ->first();
        */
          //En caso de que el operador nunca hubiese estado asignado a esta caja, lo asigna
          $operador_id=$operador['id'] ?? null;
          $operadorAsignado = OperadorAsignado::updateOrCreate(['id'=>$operador_id],$operador);
          //$operadorAsignado->save();
          $operadores->push($operadorAsignado);
          $operadorer_id[]=$operador['id'];
        
        }
        return OperadorAsignadoResource::collection($operadores);
        
        /*
      try {
        
      } catch (Exception $ex) {
        return response()->json([
          'error' => 'Ocurrio un error durante la asignacion del operador.'
      ], 500);
      }
      */
   }

   public function retirarAsignacionService(array $data)
   {
    try {
      //return $data;
        //Consulta si el operador esta o estuvo asignado a la caja
        $operadorAsignado = OperadorAsignado::withTrashed()
        ->where('id_caja_catalogo',$data['id_caja_catalogo'])
        ->where('id_operador',$data['id_operador'])
        ->first();

        //Comprueba que exista registro el operador
        if ($operadorAsignado) {

          //Comprueba que el operador no cuente con un retiro de asignacion previo
          if ($operadorAsignado->trashed()) {

            return response()->json([
              'El operador seleccionado no cuenta con asignacion a esta caja.'
            ]);
          }
          else{
            //Si el operador no cuenta con asignacion retirada previa retira su asignacion
            $operadorAsignado->delete();
            return response()->json([
              'Se retiro la asignacion del operador.'
            ]);
          }
        }
        else{
          //Si el operador nunca ha estado asignado a esta caja, muestra este mensaje
          return response()->json([
            'El operador seleccionado no cuenta con asignacion a esta caja.'
          ]);
        }
    } catch (Exception $ex) {
       return response()->json([
          'error' => 'Ocurrio un error al retirar la asignacion.'
      ], 500);
    }
  }

  public function consultarCajasCatalogo() //pendiente modificar resource
  {
    try {
      //return CajaCatalogo::with('operadorAsignado.operador')->orderby("id", "desc")->get();
      
      return response(CajaCatalogoResource::collection(
        CajaCatalogo::with('operadorAsignado.operador')->orderby("id", "desc")->get()
    ));
    
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al retirar la asignacion.'
    ], 500);
    }
  }

  public function guardarCajaCatalogoService(array $data)
  {
 
    //$caja->save();
    try {
     
      $cajaPrevia = CajaCatalogo::withTrashed()
      ->where('nombre_caja',$data["nombre_caja"])
      ->first();

      //return $cajaPrevia;

      if ($cajaPrevia) {

          if ($cajaPrevia->trashed()) {
              return response()->json([
              'message' => 'La caja ya existe pero ha sido eliminada, ¿Desea restaurarla?',
              'restore' => true,
              'caja_id' => $cajaPrevia->id
             ], 200);
          }

          return response()->json([
           'error' => 'La caja ya existe.'
          ], 500); 

      }else{
        
      //return $data;
        $caja = CajaCatalogo::create($data);
        return response(new CajaCatalogoResource($caja), 200);;
        return $caja;

        return response()->json([
          'Se ha registrado la caja en el catalogo.'
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
                'error' => 'Ocurrio un error durante el registro de la caja.'.$ex
            ], 500);
    }
  }

  public function eliminarCajaCatalogoService(string $id)
  {
    try {
      $caja = CajaCatalogo::find($id);
     
      if ($caja) {

        $caja->delete();
        return response()->json([
         'Se ha eliminado la caja.'
       ]);

      }else{
        
       return response()->json([
         'No se ha encontrado la caja.'
      ]);
      }
      //$caja->restore();
    } catch (Exception $ex) {
      return response()->json([
          'error' => 'No se ha eliminado la caja.'
      ]);
    }
  }
  public function restaurarCajaCatalogoService($id)
  {
    try {
      $caja = CajaCatalogo::withTrashed()->findOrFail($id);
      //Condicion para verificar si el registro esta eliminado
      if ($caja->trashed()) {
         //Restaura el registro
         $caja->restore();

           return response()->json([
            'Se ha restaurado la caja.'
           ]);
       }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al restaurar la caja.'
      ]);
    }
  }


  public function modificarCajaCatalogoService(array $data, string $id)
  {
    try {
      $caja = CajaCatalogo::find($id);
      if ($caja) {
        $caja->update($data);
        $caja->save();
        return new CajaCatalogoResource($caja);
      }
      else{
        return response()->json([
          'error' => 'No se encontro la caja que desea modificar.'
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al modificar la caja.'
      ]);
    }
  }

  public function mostrarCajaService(string $id)
  {
    try {
      $caja = CajaCatalogo::find($id);
      if ($caja) {
        return new CajaCatalogoResource($caja);
      }else{
        return response()->json([
          'error' => 'No se encontro la caja en el catalogo.'
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la busqueda de la caja.'
    ], 500);
    }
  }

  public function buscarSesionCajaService(Request $data)
  {
    try {
     // return $data->fecha_apertura; new CajaResource($cajaSesion);
      $cajaSesion = Caja::where('id_operador',$data->id_operador)
      ->where('id_caja_catalogo',$data->id_caja_catalogo)
      ->where(DB::raw('DATE(fecha_apertura)'),$data->fecha_apertura)
      ->where('fecha_cierre',null)
      ->first();
      return (new CajaResource($cajaSesion));
    } catch (Exception $ex) {
      
    }
  }

}