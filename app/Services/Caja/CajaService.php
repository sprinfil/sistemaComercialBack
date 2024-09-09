<?php

namespace App\Services\Caja;

use App\Http\Requests\StoreRetiroCajaRequest;
use App\Http\Requests\StoreSolicitudCancelacionRequest;
use App\Http\Resources\CajaCatalogoResource;
use App\Http\Resources\CajaResource;
use App\Http\Resources\CorteCajaResource;
use App\Http\Resources\OperadorAsignadoResource;
use App\Http\Resources\PagoResource;
use App\Http\Resources\RetiroCajaResource;
use App\Http\Resources\SolicitudCancelacionPagoResource;
use App\Models\Caja;
use App\Models\CajaCatalogo;
use App\Models\CorteCaja;
use App\Models\OperadorAsignado;
use App\Models\Pago;
use App\Models\RetiroCaja;
use App\Models\SolicitudCancelacionPago;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class CajaService
{

  public function iniciarCaja(array $data)
  {
    try {

       
        //Fecha hora base
        $fechaHoraBase = Carbon::now();
        $fechaHoraLocal = $fechaHoraBase->setTimezone('America/Tijuana');
        $fechaHoraLocalFormateada = $fechaHoraLocal->format('Y-m-d H:i:s');

      //Fecha hora base
      $fechaHoraBase = Carbon::now();
      $fechaHoraLocal = $fechaHoraBase->setTimezone('America/Tijuana');
      $fechaHoraLocalFormateada = $fechaHoraLocal->format('Y-m-d H:i:s');

      //formateo de la hora de apertura a horas-minutos-segundos
      $horaApertura = Carbon::parse($fechaHoraLocalFormateada);
      $horaApertura = $horaApertura->format('H:i:s');

        if ($cajaAsignada == null) {
          return response()->json([
            'error' => 'El operador no esta asignado a esta caja.'
           ]);
        }
        
        //Obtencion de la informacion de la caja en la que se va a registrar
        $CajaCatalogo = CajaCatalogo::FindOrFail($cajaAsignada->id_caja_catalogo);
       
        //Obtencion de un registro previo en caso de existir uno en el mismo dia y que se encuentre en estado activo
        $cajaPreviaReg = Caja::where('id_operador',$idOperador) 
        ->whereDate('fecha_apertura',  $fechaApertura)
        ->where('estado',  "activo")
        ->get();
       
            //El id_operador ingresado debe ser igual al de la caja en la que se quiere logear
        if($cajaAsignada->id_operador == $idOperador)
        {
            //Se debe estar intentando ingresar entre las horas autorizadas en la caja del catalogo de cajas
          if($horaApertura >= $CajaCatalogo->hora_apertura && $horaApertura <= $CajaCatalogo->hora_cierre){

            //No debe existir un registro previo de la caja en el mismo dia a menos que este registro ya cuente con una fecha de cierre
           if (count($cajaPreviaReg) == 0) {
            
             $sesionCaja = [
               "id_operador" =>$idOperador,
               "id_caja_catalogo" => $data['id_caja_catalogo'], 
               "fondo_inicial" => $data['fondo_inicial'],
               "fecha_apertura" => $fechaHoraLocalFormateada ,
              ]; 

                $caja = Caja::create($sesionCaja);
                return response(new CajaResource($caja));;
              }
              
           //condicion en caso de que la caja ya cuente con un registro abierto el dia actual
           else{
             return response()->json([
               'error' => 'La caja ya se encuentra abierta.',$cajaPreviaReg
              ]);
           }
           
          }
        }
        //Condicion si el operador no esta intentanto abrir la caja en horario de cobro
        else {
          return response()->json([
            'error' => 'La caja no puede abrir fuera del horario de cobro.'
          ]);
        }
      }
      //condicion si el operador no esta asignado a la caja
      else {
        return response()->json([
            'error' => 'Ocurrio un error durante la apertura.'.$ex
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

      //Obtencion del usuario y de su operador asociado
      $usuario = auth()->user();
      $idOperador = $usuario->operador->id;

        $discrepancia = "no";
        $discrepanciaMonto = 0;
        
         //Fecha hora base
         $fechaHoraBase = Carbon::now();
         $fechaHoraLocal = $fechaHoraBase->setTimezone('America/Tijuana');
         $fechaHoraLocalFormateada = $fechaHoraLocal->format('Y-m-d H:i:s');
        
         //formateo de la fecha años-meses-dias
         $fechaApertura = Carbon::parse($fechaHoraLocalFormateada);
         $fechaApertura = $fechaApertura->format('Y-m-d');
        
        //Consulta de registro de caja por idOperador,idCajaCatalogo donde el registro sea del dia actual y no cuente con fecha de cierre
        $cajaHisto = Caja::where('id_operador',$idOperador)
        ->where('id_caja_catalogo',$data['caja_data'][0]['id_caja_catalogo'])
        ->whereDate('fecha_apertura',$fechaApertura)
        ->where('estado','activo')
        ->first();

        //Verifica que exista un registro caja el cual finalizar 
        if ($cajaHisto) {
        
          //Obtiene el total de dinero registrado en los pagos
          $totalRegistrado = $cajaHisto->totalPorTipo("efectivo") 
          + $cajaHisto->totalPorTipo("tarjeta_credito") 
          + $cajaHisto->totalPorTipo("tarjeta_debito") 
          + $cajaHisto->totalPorTipo("cheque")
          + $cajaHisto->totalPorTipo("transferencia")
          + $cajaHisto->totalPorTipo("documento");

            "cantidad_centavo_10" => $data['corte_data'][0]['cantidad_centavo_10'],
            "cantidad_centavo_20" => $data['corte_data'][0]['cantidad_centavo_20'],
            "cantidad_centavo_50" => $data['corte_data'][0]['cantidad_centavo_50'],
            "cantidad_moneda_1" => $data['corte_data'][0]['cantidad_moneda_1'],
            "cantidad_moneda_2" => $data['corte_data'][0]['cantidad_moneda_2'],
            "cantidad_moneda_5" => $data['corte_data'][0]['cantidad_moneda_5'],
            "cantidad_moneda_10" => $data['corte_data'][0]['cantidad_moneda_10'],
            "cantidad_moneda_20" => $data['corte_data'][0]['cantidad_moneda_20'],
            "cantidad_billete_20" => $data['corte_data'][0]['cantidad_billete_20'],
            "cantidad_billete_50" => $data['corte_data'][0]['cantidad_billete_50'],
            "cantidad_billete_100" => $data['corte_data'][0]['cantidad_billete_100'],
            "cantidad_billete_200" => $data['corte_data'][0]['cantidad_billete_200'],
            "cantidad_billete_500" => $data['corte_data'][0]['cantidad_billete_500'],
            "cantidad_billete_1000" => $data['corte_data'][0]['cantidad_billete_1000'],
            "total_efectivo_registrado" =>  $cajaHisto->totalPorTipo("efectivo") ?? 0,
            "total_efectivo_real" => $data['corte_data'][0]['total_efectivo_real']?? 0,
            "total_tarjetas_credito_registrado" => $cajaHisto->totalPorTipo("tarjeta_credito") ?? 0,
            "total_tarjetas_credito_real" =>$data['corte_data'][0]['total_tarjetas_credito_real'] ?? 0,
            "total_tarjetas_debito_registrado" => $cajaHisto->totalPorTipo("tarjeta_debito"),
            "total_tarjetas_debito_real" =>$data['corte_data'][0]['total_tarjetas_debito_real'] ?? 0,
            "total_cheques_registrado" => $cajaHisto->totalPorTipo("cheque") ?? 0,
            "total_cheques_real" =>$data['corte_data'][0]['total_cheques_real'] ?? 0,
            "total_transferencias_registrado" => $cajaHisto->totalPorTipo("transferencia") ?? 0,
            "total_transferencias_real" =>$data['corte_data'][0]['total_transferencias_real'] ?? 0,
            "total_documentos_registrado" => $cajaHisto->totalPorTipo("documento") ?? 0,
            "total_documentos_real" =>$data['corte_data'][0]['total_documentos_real'] ?? 0,


            "total_registrado" => $totalRegistrado,
            "total_real" => $data['corte_data'][0]['total_real'],
            "discrepancia" => $discrepancia,
            "discrepancia_monto" => $discrepanciaMonto,
            "fecha_corte" => $fechaHoraLocalFormateada
            
          ]; 

          $cajaReg = [
            "id_caja_catalogo" => $data['caja_data'][0]['id_caja_catalogo'],
            "fondo_final" => $data['caja_data'][0]['fondo_final'],
            "fecha_cierre" => $fechaHoraLocalFormateada,
            "estado" => 'inactivo'
          ];
          
           //Registra el corte y actualiza el cierre de caja
           $cajaHisto->update($cajaReg);
           $corte = CorteCaja::create($corteReg);
           $corte->save();
           //Mensaje de exito
           return response()->json([
            'Se ha finalizado el cierre de caja y se registro el corte.'
           ]);
                   
        }else{
          return response()->json([
            'error' => 'No existen cajas abiertas.'
           ]);
        }

        //Crea el registro de corte de caja
        $corteReg = [
          "id_caja" => $cajaHisto->id,
          "id_operador" => $idOperador,
          "estatus" => "pendiente",

          "cantidad_centavo_10" => $data['corte_data'][0]['cantidad_centavo_10'],
          "cantidad_centavo_20" => $data['corte_data'][0]['cantidad_centavo_20'],
          "cantidad_centavo_50" => $data['corte_data'][0]['cantidad_centavo_50'],
          "cantidad_moneda_1" => $data['corte_data'][0]['cantidad_moneda_1'],
          "cantidad_moneda_2" => $data['corte_data'][0]['cantidad_moneda_2'],
          "cantidad_moneda_5" => $data['corte_data'][0]['cantidad_moneda_5'],
          "cantidad_moneda_10" => $data['corte_data'][0]['cantidad_moneda_10'],
          "cantidad_moneda_20" => $data['corte_data'][0]['cantidad_moneda_20'],
          "cantidad_billete_20" => $data['corte_data'][0]['cantidad_billete_20'],
          "cantidad_billete_50" => $data['corte_data'][0]['cantidad_billete_50'],
          "cantidad_billete_100" => $data['corte_data'][0]['cantidad_billete_100'],
          "cantidad_billete_200" => $data['corte_data'][0]['cantidad_billete_200'],
          "cantidad_billete_500" => $data['corte_data'][0]['cantidad_billete_500'],
          "cantidad_billete_1000" => $data['corte_data'][0]['cantidad_billete_1000'],
          "total_efectivo_registrado" =>  $cajaHisto->totalPorTipo("efectivo") ?? 0,
          "total_efectivo_real" => $data['corte_data'][0]['total_efectivo_real'] ?? 0,
          "total_tarjetas_credito_registrado" => $cajaHisto->totalPorTipo("tarjeta_credito") ?? 0,
          "total_tarjetas_credito_real" => $data['corte_data'][0]['total_tarjetas_credito_real'] ?? 0,
          "total_tarjetas_debito_registrado" => $cajaHisto->totalPorTipo("tarjeta_debito"),
          "total_tarjetas_debito_real" => $data['corte_data'][0]['total_tarjetas_debito_real'] ?? 0,
          "total_cheques_registrado" => $cajaHisto->totalPorTipo("cheque") ?? 0,
          "total_cheques_real" => $data['corte_data'][0]['total_cheques_real'] ?? 0,
          "total_transferencias_registrado" => $cajaHisto->totalPorTipo("transferencia") ?? 0,
          "total_transferencias_real" => $data['corte_data'][0]['total_transferencias_real'] ?? 0,
          "total_documentos_registrado" => $cajaHisto->totalPorTipo("documento") ?? 0,
          "total_documentos_real" => $data['corte_data'][0]['total_documentos_real'] ?? 0,


          "total_registrado" => $totalRegistrado,
          "total_real" => $data['corte_data'][0]['total_real'],
          "discrepancia" => $discrepancia,
          "discrepancia_monto" => $discrepanciaMonto,
          "fecha_corte" => $fechaHoraLocalFormateada
        ];
        $cajaReg = [
          "id_caja_catalogo" => $data['caja_data'][0]['id_caja_catalogo'],
          "fondo_final" => $data['caja_data'][0]['fondo_final'],
          "fecha_cierre" => $fechaHoraLocalFormateada
        ];


        //Registra el corte y actualiza el cierre de caja
        $cajaHisto->update($cajaReg);
        $corte = CorteCaja::create($corteReg);
        $corte->save();
        //Mensaje de exito
        return response()->json([
          'Se ha finalizado el cierre de caja y se registro el corte.'
        ]);
      } else {
        return response()->json([
          'error' => 'No existen cajas abiertas.'
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error al realizar el cierre de caja.' . $ex
      ]);
    }
  }


  public function pagosPorCaja(Request $request)
  {
    try {
      $data = $request->all();
      $id_caja = $data['id_caja'];
      $pagos = Caja::findOrFail($id_caja)->pagosConDetalle;
      return $pagos;
    } catch (Exception $ex) {
      throw $ex;
    }
  }

  public function cargoPorCaja(Request $request)
  {
    try {
      $data = $request->all();
      $id_caja = $data['id_caja'];
      $cargos = Caja::findOrFail($id_caja)->cargos;
      return $cargos;
    } catch (Exception $ex) {
      throw $ex;
    }
  }

  public function solicitudCancelacionPago(StoreSolicitudCancelacionRequest $request)
  {
    try {
      $data = $request->validated();
      $data['estado'] = 'pendiente';
      return SolicitudCancelacionPago::create($data);
    } catch (Exception $ex) {
      throw $ex;
    }
  }

  public function solicitudesCancelacion(Request $request)
  {
    try {
      // Comienza una consulta base para las solicitudes
      $query = SolicitudCancelacionPago::query();

      // Aplica el filtro por id_solicitante si está presente en la solicitud
      if ($request->has('id_solicitante')) {
        $query->where('id_solicitante', $request->input('id_solicitante'));
      }

      // Aplica el filtro por id_revisor si está presente en la solicitud
      if ($request->has('id_revisor')) {
        $query->where('id_revisor', $request->input('id_revisor'));
      }

      // Aplica el filtro por id_caja si está presente en la solicitud
      if ($request->has('id_caja')) {
        $query->where('id_caja', $request->input('id_caja'));
      }

      // Aplica el filtro por folio si está presente en la solicitud
      if ($request->has('folio')) {
        $query->where('folio', $request->input('folio'));
      }

      // Aplica el filtro por estado si está presente en la solicitud
      if ($request->has('estado')) {
        $query->where('estado', $request->input('estado'));
      }

      // Aplica el filtro por fecha_solicitud si está presente en la solicitud
      if ($request->has('fecha_solicitud')) {
        $query->whereDate('created_at', $request->input('fecha_solicitud'));
      }

      // Aplica el filtro por fecha_actualizacion si está presente en la solicitud
      if ($request->has('fecha_actualizacion')) {
        $query->whereDate('updated_at', $request->input('fecha_actualizacion'));
      }

      // Ejecuta la consulta y obtiene los resultados
      $solicitudes = $query->get();

      // Retorna las solicitudes con el resource
      return SolicitudCancelacionPagoResource::collection($solicitudes);
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrió un error durante la búsqueda de las solicitudes'
      ], 500);
    }
  }

  public function actualizarSolicitudCancelacion(Request $request)
  {
    try {
      DB::beginTransaction();

      if ($request->has('id')) {
        $solicitud = SolicitudCancelacionPago::findOrFail($request->input('id'));
        $pago = $solicitud->pago;
        $usuario = auth()->user();
        $idOperador = $usuario->operador->id;

        // Verifica si el pago ya está cancelado
        if ($pago->estado == 'cancelado') {
          throw new Exception("este pago ya fue cancelado");
        }

        // Accede al estado del request
        $estado = $request->input('estado');

        // Verifica si el estado es aprobado
        if ($estado == 'aprobado') {
          $pago->estado = "cancelado";
          $solicitud->id_revisor = $idOperador;
          $solicitud->estado = 'aprobado';
          $solicitud->save();
          $pago->save();

          $consolidacion = (new PagoService())->cancelarPagoYConsolidarCargos($pago->id);
          DB::commit();
          return $consolidacion;
        }
        // Verifica si el estado es rechazado
        else if ($estado == 'rechazado') {
          $solicitud->id_revisor = $idOperador;
          $solicitud->estado = 'rechazado';
          $solicitud->save();
          DB::commit();
        }
      } else {
        throw new Exception("no hay id de solicitud");
      }
    } catch (Exception $ex) {
      DB::rollBack();
      return response()->json([
        'error' => $ex->getMessage()
      ], 400);
    }
  }

  public function asignarOperadorService(array $data)
  {
    $operadores = new Collection();
    $operadores_id = [];
    foreach ($data as $operador) {


      //En caso de que el operador nunca hubiese estado asignado a esta caja, lo asigna
      $operador_id = $operador['id'] ?? null;
      $operadorAsignado = OperadorAsignado::updateOrCreate(['id_caja_catalogo' => $operador['id_caja_catalogo'], 'id_operador' => $operador['id_operador']], $operador);
      //$operadorAsignado->save();
      $operadores->push($operadorAsignado);
      $operadores_id[] = $operadorAsignado['id'];
    }
    OperadorAsignado::where('id_caja_catalogo', $operadores[0]['id_caja_catalogo'])
      ->whereNotIn('id', $operadores_id)
      ->delete();
    return OperadorAsignadoResource::collection($operadores);
  }

  public function retirarAsignacionService(array $data)
  {
    try {
      //return $data;
      //Consulta si el operador esta o estuvo asignado a la caja
      $operadorAsignado = OperadorAsignado::withTrashed()
        ->where('id_caja_catalogo', $data['id_caja_catalogo'])
        ->where('id_operador', $data['id_operador'])
        ->first();

      //Comprueba que exista registro el operador
      if ($operadorAsignado) {

        //Comprueba que el operador no cuente con un retiro de asignacion previo
        if ($operadorAsignado->trashed()) {

          return response()->json([
            'El operador seleccionado no cuenta con asignacion a esta caja.'
          ]);
        } else {
          //Si el operador no cuenta con asignacion retirada previa retira su asignacion
          $operadorAsignado->delete();
          return response()->json([
            'Se retiro la asignacion del operador.'
          ]);
        }
      } else {
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
 
    try {

      $cajaPrevia = CajaCatalogo::withTrashed()
        ->where('nombre_caja', $data["nombre_caja"])
        ->first();

      if ($cajaPrevia) {

        if ($cajaPrevia->trashed()) {
          return response()->json([
            'message' => 'La caja ya existe pero ha sido eliminada, ¿Desea restaurarla?',
            'restore' => true,
            'caja_id' => $cajaPrevia->id
          ], 200);
        }

      }else{
        
        $caja = CajaCatalogo::create($data);
        return response(new CajaCatalogoResource($caja), 200);;
        return $caja;

        return response()->json([
          'Se ha registrado la caja en el catalogo.'
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
                'error' => 'Ocurrio un error durante el registro de la caja.'
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
      } else {

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
      } else {
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
      } else {
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

  public function buscarSesionCajaService(Request $data) //aqui
  {

    try {
      $usuario = auth()->user();
      $idOperador = $usuario->operador->id;
      
     // return $data->fecha_apertura; new CajaResource($cajaSesion);
     //Fecha hora base
     $fechaHoraBase = Carbon::now();
     $fechaHoraLocal = $fechaHoraBase->setTimezone('America/Tijuana');
     $fechaHoraLocalFormateada = $fechaHoraLocal->format('Y-m-d H:i:s');
    
     //formateo de la fecha años-meses-dias
     $fechaApertura = Carbon::parse($fechaHoraLocalFormateada);
     $fechaApertura = $fechaApertura->format('Y-m-d');
     
      $cajaSesion = Caja::where('id_operador',$idOperador)
      ->where('id_caja_catalogo',$data->id_caja_catalogo)
      ->where(DB::raw('DATE(fecha_apertura)'),$fechaApertura)
      ->where('estado','activo')
      ->first();
      
      if ($cajaSesion) {
        return (new CajaResource($cajaSesion));
      } else {
        return response()->json([
          'error' => 'No se encontro sesion de caja abierta asociada a este operador.'
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la busqueda de  sesion de la caja.'
      ], 500);
    }
  }

  //RetiroMetodos
  public function registrarRetiroService(array $data)
  {

    try {
      $usuario = auth()->user();
      $idOperador = $usuario->operador->id;

      //Fecha hora base
      $fechaHoraBase = Carbon::now();
      $fechaHoraLocal = $fechaHoraBase->setTimezone('America/Tijuana');
      $fechaHoraLocalFormateada = $fechaHoraLocal->format('Y-m-d H:i:s');

      //formateo de la fecha años-meses-dias
      $fechaApertura = Carbon::parse($fechaHoraLocalFormateada);
      $fechaApertura = $fechaApertura->format('Y-m-d');
      
      if ($data) {
        
        $cajaSesion = Caja::where('id_operador',$idOperador)
        ->where('id_caja_catalogo',$data['id_caja_catalogo'])
        ->where(DB::raw('DATE(fecha_apertura)'),$fechaApertura)
        ->where('estado','activo')
        ->first();
  
        if ($cajaSesion->fecha_cierre == null) {

          $retiroArray = [
            "id_sesion_caja" => $cajaSesion->id,
            "cantidad_centavo_10" => $data['cantidad_centavo_10'],
            "cantidad_centavo_20" => $data['cantidad_centavo_20'],
            "cantidad_centavo_50" => $data['cantidad_centavo_50'],
            "cantidad_moneda_1" => $data['cantidad_moneda_1'],
            "cantidad_moneda_2" => $data['cantidad_moneda_2'],
            "cantidad_moneda_5" => $data['cantidad_moneda_5'],
            "cantidad_moneda_10" => $data['cantidad_moneda_10'],
            "cantidad_moneda_20" => $data['cantidad_moneda_20'],
            "cantidad_billete_20" => $data['cantidad_billete_20'],
            "cantidad_billete_50" => $data['cantidad_billete_50'],
            "cantidad_billete_100" => $data['cantidad_billete_100'],
            "cantidad_billete_200" => $data['cantidad_billete_200'],
            "cantidad_billete_500" => $data['cantidad_billete_500'],
            "cantidad_billete_1000" => $data['cantidad_billete_1000'],
            "monto_total" => $data['monto_total'],

          ];

          $retiroCaja = RetiroCaja::create($retiroArray);
          return response(new RetiroCajaResource($retiroCaja));
        } else {

          return response()->json([
            "error" => 'No existe sesion activa asociada a este retiro.'
          ]);
        }
      } else {
        return response()->json([
          "error" => 'No existen datos a registrar.'
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante el registro del retiro.'
      ], 500);
    }
  }

  public function estadoSesionCobroService(Request $data)
  {
    try {
      //recibir id sesion de caja
        $usuario = auth()->user();
        $montoTotal = 0;
        $dataArreglo = $data->toArray();
        if ($usuario->operador) {

          if($dataArreglo['id_sesion_caja'] != null)
          {
            $sesionCaja = Caja::where('id',$dataArreglo['id_sesion_caja'])->first();
           
            if($usuario->operador->id != $sesionCaja->id_operador)
            {
              return response()->json([
                'error'=>'El operador no tiene acceso a las sesiones de otros operadores'
              ]);
            }
          
          }else{
            $sesionCaja = Caja::where('id_operador',$usuario->operador->id)
           ->where('estado','activo')
           ->first();
          }
          
          if ($sesionCaja) {
            
            //Consulta del nombre de la caja
            $cataCajaNombre = CajaCatalogo::select('nombre_caja') 
            ->where('id',$sesionCaja->id_caja_catalogo)
            ->first();

          //Consulta de los montos de los retiros
          $retiro = RetiroCaja::select('monto_total')
            ->where('id_sesion_caja', $sesionCaja->id)
            ->get();

          //Nombre del operador
          $NombreOperador = $usuario->operador->nombre . " "
            . $usuario->operador->apellido_paterno . " "
            . $usuario->operador->apellido_materno;

            $fondoInicial = $sesionCaja->fondo_inicial;           
            //Proceso para sumar los totales de todos los retiros asociados a la sesion de cobro
            if ($retiro) {
              $retirosMonto = $retiro->toArray();
              foreach ($retirosMonto as $monto)
              {
                $montoTotal += $monto['monto_total'];
              }
            }else{
              $montoTotal = 0;
            }
                    
            //Datos a retornar
            $estadoSesion = [
              "caja_nombre" => $cataCajaNombre->nombre_caja, 
              "nombre_operador" => $NombreOperador,
              "id_operador" => $usuario->operador->id,
              "fondo_inicial" => $fondoInicial,
              "Total_retirado" => $montoTotal,
              "id" =>  $sesionCaja->id
            ];

            return $estadoSesion;

          //Proceso para sumar los totales de todos los retiros asociados a la sesion de cobro
          $retirosMonto = $retiro->toArray();
          foreach ($retirosMonto as $monto) {
            $montoTotal += $monto['monto_total'];
          }

          //Datos a retornar
          $estadoSesion = [
            "caja_nombre" => $cataCajaNombre->nombre_caja,
            "nombre_operador" => $NombreOperador,
            "id_operador" => $usuario->operador->id,
            "fondo_inicial" => $fondoInicial,
            "Total_retirado" => $montoTotal,
            "id" =>  $sesionCaja->id
          ];

          return $estadoSesion;
        }
      } else {
        return response()->json([
          'error' => 'Este usuario no es un operador '
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la busqueda.'.$ex
    ], 500);
    }
  }

  public function sesionPreviaService()
  {
    try {
      $usuario = auth()->user();
      //Obtencion de datos del arreglo
      $idOperador = $usuario->operador->id;

      $sesionesAbiertas = Caja::where('id_operador',$idOperador)
      ->where('estado','activo')
      ->get();

      if ($sesionesAbiertas) {
        return json_encode($sesionesAbiertas);
      } else {
        return response()->json([
          'error' => 'No se existen sesiones abiertas.'
        ]);
      }
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la busqueda.'
      ], 500);
    }
  }

  public function cortesRechazadosService()
  {
    try {
      $usuario = auth()->user();
      //Obtencion de datos del arreglo
      $idOperador = $usuario->operador->id;

      $cortesRechazados = CorteCaja::where('id_operador', $idOperador)
        ->where('estatus', "rechazado")
        ->with('Caja', 'Caja.catalogoCaja')
        ->get();
      return json_encode($cortesRechazados);
    } catch (Exception $ex) {
      return response()->json([
        'error' => 'Ocurrio un error durante la busqueda.'
      ], 500);
    }
  }
}
