<?php
namespace App\Services\Facturacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTarifaConceptoDetalleRequest;
use App\Models\tarifa;
use Illuminate\Http\Request;
use App\Http\Requests\StoretarifaRequest;
use App\Http\Requests\StoreTarifaServiciosDetalleRequest;
use App\Http\Requests\UpdateTarifaConceptoDetalle;
use App\Http\Requests\UpdatetarifaRequest;
use App\Http\Requests\UpdateTarifaServiciosDetalleRequest;
use App\Http\Resources\StoreTarifaConceptoDetalleResource;
use App\Http\Resources\TarifaConceptoDetalleResource;
use App\Http\Resources\TarifaResource;
use App\Http\Resources\TarifaServiciosDetalleResource;
use App\Models\ConceptoCatalogo;
use App\Models\TarifaConceptoDetalle;
use App\Models\TarifaServicio;
use App\Models\TarifaServiciosDetalle;
use App\Models\TipoToma;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use COM;


class TarifaService{


    public function indexTarifaService()
    {
       
       try {
        return TarifaResource::collection(
            Tarifa::all()
        );
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron no se encontraron registros de tarifas.'
        ], 200);
       }
        

    }

    public function storeTarifaService(array $data, string $nombre, Request $request)
    {
        try {       
             //Busca por nombre las tarifas eliminadas
            $tarifa = Tarifa::withTrashed()->orWhere('nombre', $nombre)->first();
            //VALIDACION POR SI EXISTE
            if ($tarifa) {
                if ($tarifa->trashed()) {
                     $tarifa->restore(); //Restaura la tarifa
                    return response()->json([
                        'message' => 'La tarifa ya existe pero ha sido eliminada. ¿Desea restaurarla?',
                        'restore' => true,
                        'tarifa' => $tarifa->id
                    ], 200);
                 }
                return response()->json([
                    'message' => 'La tarifa ya existe.',
                    'restore' => false
                ], 500);
            }
            //Si no existe la tarifa, crea una tarifa
            if (!$tarifa) {
                //$tarifa = Tarifa::create($data);
                // E importa las tarifas de la tarifa activa si se desea
                $request = new Request();
                $request->merge(['confirm' => true]);
                //$request->merge(['tarifa_id' => $tarifa->id]);
                $respuesta = $this->importarTipoTomaTarifas($request);
                return response($respuesta, 201);
            }
        } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar la tarifa.'
             ], 500);
        }              
    }

    public function importarTipoTomaTarifas(Request $request){
        try{
            //input con un tarifa_id
            //$id_tarifa_nueva = $request->input('tarifa_id');
            $confirm = $request->input('confirm');
            //Si el Confirm del request es true y la tarifa_id es diferente de null entra a la condicion
            if($confirm != null){
                $tarifas_tipo = TipoToma::all(); //Consulta todos los tipos de tomas que hay registrados
                $tarifa_activa = Tarifa::where('estado', 'activo')->first(); //Busca la tarifa que tenga el estado = activo
                foreach ($tarifas_tipo as $tipo) {
                    
                    // y al final los servicios por tipo de toma, en la tarifa activa
                    return $servicios = TarifaServiciosDetalle::with('tarifaServicio', 'tarifaServicio.tipotoma' ,
                     'tarifaServicio.tarifa',
                     'tarifaServicio.tarifa.conceptos')
                    ->where('id_tarifa_servicio' , $tarifa_activa->id)
                    ->get();

                    foreach ($servicios as $servicio) {
                        $detalle_servicio = new TarifaServiciosDetalle();
                        //$detalle_servicio->id_tarifa_servicio = $id_tarifa_nueva;
                        $detalle_servicio->rango = $servicio['rango'];
                        $detalle_servicio->monto = $servicio['monto'];
                        //$detalle_servicio->save();
                    }

                    // valida si hay tarifas para ese tipo de toma
                    if(count($servicios) < 1){
                        return response()->json([
                            'error' => 'No hay servicios importables de tipo '.$tipo->id,
                            'import' => false
                        ], 500);
                    }
                }
                return response()->json([
                    'message' => 'Se han importado las tarifas',
                    'import' => $request->input('confirm')
                ], 200);
            }
            else {
                return response()->json([
                    'message' => 'Registros en blanco',
                    'import' => $request->input('confirm')
                ], 200);
            }
        }catch(Exception $ex){
            return response()->json([
                'error' => 'Error al crear las tarifas'.$ex,
                'import' => false
            ], 500);
        }
    }

    public function showTarifaService($tarifa)
    {

        try {
            $tarifa = Tarifa::findOrFail($tarifa);
            return response(new TarifaResource($tarifa), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la tarifa'
            ], 500);
        }

    }

    

    public function updateTarifaService(Request $request, $id)
    { 
        try {
            $tarifa = Tarifa::findOrFail($id);
            $data = $request->all();
            $estado = $request->input('ConfirmUpdate');
            if ($tarifa) {
                $tarifa->update($data);
                if ($estado == 'activo') {
                    Tarifa::where('estado', 'activo')
                    ->where('id', '!=', $tarifa->id)
                    ->update(['estado' => 'inactivo']);
                    $tarifa->estado = 'activo';
                    return response()->json([
                        'id' => $tarifa->id,
                        'nombre' => $tarifa->nombre,
                        'descripcion' => $tarifa->descripcion,
                        'fecha' => $tarifa->fecha,
                        'estado' => $tarifa->estado,
                        'ConfirmUpdate' => true,
                        'message' => 'Se actualizo el estado de la tarifa',
                    ], 200);
                }
                elseif($estado == 'inactivo'){
                    $tarifa->estado = 'inactivo';
                }
                $tarifa->save();
                return response(new TarifaResource($tarifa), 200);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo editar la tarifa '
            ], 400);
        }
           
    }

    public function actualizarEstadoTarifaService(Request $request)
    {
        try {
            //obtenemos la respuesta
            $tarifaId = $request->input('tarifa_id');
            $tarifa = Tarifa::find($tarifaId);
            if ($tarifa) {
              if ($request->input('confirmUpdate')) {
                // inactivar todo TODO
                Tarifa::where('estado', 'activo')->update(['estado' => 'inactivo']);
                // obtener el fakin id                
                    $tarifa->estado = 'activo';
                    $tarifa->save();
                return response()->json([
                    'message' => 'Actualización realizada con éxito.',
                ], 200);
            }
        }
            else{
                return response()->json([
                    'message' => 'No se realizó ninguna actualización.' 
                ], 200);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo editar la tarifa: ' . $ex->getMessage(),
            ], 500);
        }
    }


    public function destroyTarifaService($id)
    {
        //$this->authorize('delete', tarifa::class);
        try {
            $tarifa = Tarifa::findOrFail($id);
            $tarifa->delete();
            return response()->json(['message' => 'La tarifa se ha eliminado con exito. ']);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'error' => 'No se ha removido la tarifa'
            ], 500);
        }
        
    }

    public function restaurarTarifaService(string $id)
    {

        try {
            $tarifa = Tarifa::withTrashed()->findOrFail($id);

            // Verifica si el registro está eliminado
            if ($tarifa->trashed()) {

              // Restaura el registro
              $tarifa->restore();
              return response()->json(['message' => 'La tarifa ha sido restaurada.'], 200);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se ha restaurado la tarifa.'
            ], 500);
        }
        
    }

    //TARIFA_CONCEPTO_DETALLE

    public function indexTarifaConceptoDetalleService()
    {
        try {
            return TarifaConceptoDetalleResource::collection(
                TarifaConceptoDetalle::all()
            );
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se han encontrado conceptos de la tarifa.'
            ], 500);
        }
        
    }

    public function storeTarifaConceptoDetalleService(array $data)
    {
        
        try {
            //VALIDA EL STORE
            $tarifaConceptoDetalle = TarifaConceptoDetalle::create($data);
            return response(new TarifaConceptoDetalleResource($tarifaConceptoDetalle), 201);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo guardar el concepto detalle de tarifa.'
            ], 500);
        }
    }

    public function showTarifaConceptoDetalleService($tarifaDetalle)
    {
        try {
            $tarifaDetalle = TarifaConceptoDetalle::findOrFail($tarifaDetalle);
            return response(new TarifaConceptoDetalleResource($tarifaDetalle), 200);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'error' => 'No se encontro el concepto asociado a la tarifa'
            ], 500);
        }
        
    }

    public function updateTarifaConceptoDetalleService(array $data,  string $id)
    {
    
        //Falta validacion que evite modificaciones si ya esta asociado a una facturacion
        try {
            $tarifaConcepto = TarifaConceptoDetalle::findOrFail($id);
            $tarifaConcepto->update($data);
            $tarifaConcepto->save();
            return response(new TarifaConceptoDetalleResource($tarifaConcepto), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se edito el concepto de tarifa'
            ], 500);
        }
    }

    //SERVICIO TARIFA DETALLE

    public function indexServicioDetalleservice()
    {
        try {
            return TarifaServiciosDetalleResource::collection(
                TarifaServiciosDetalle::all()
            );
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se encontro el registro de servicio detalle.'
            ], 500);
        }
       
    }
    public function storeTarifaServicioDetalle($id_tarifa_servicio) 
    {
        try {
            //Verifica que exista un servicio para poder añadir una tarfa servicio detalle
             $tarifaServicio = TarifaServicio::where('id' , $id_tarifa_servicio)->first();
             //Si existe una tarifa servicio, hace el store
            if ($tarifaServicio->tarifaDetalle) {
                $tarifaServicioDetalle = TarifaServiciosDetalle::create($id_tarifa_servicio);
                return response(new TarifaServiciosDetalleResource($tarifaServicioDetalle), 201);
            }
            else{
                return response()->json([
                    'message' => 'El servicio de la tarifa no existe'
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo guardar el servicio detalle. '
            ], 500);
        }
    }

    public function showTarifaServicioDetalleService($tarifaDetalle)
    {
        try {
            $tarifaDetalle = TarifaServiciosDetalle::findOrFail($tarifaDetalle);
            return response(new TarifaServiciosDetalleResource($tarifaDetalle), 200);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'error' => 'No se encontro el servicio asociado a la tarifa.'
            ], 500);
        }
    }

    public function updateTarifaServicioDetalleService(array $data,  string $id)
    {
        try {
          
            $tarifaServicioDetalle = TarifaServiciosDetalle::findOrFail($id);
            $tarifaServicioDetalle->update($data);
            $tarifaServicioDetalle->save();
            return response(new TarifaServiciosDetalleResource($tarifaServicioDetalle), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se edito el servicio.'
            ], 500);
        }
    }

    public function get_conceptos_detalles_by_tarifa_idService($tarifa_id)
    {

        try {
            $tarifa = TarifaConceptoDetalle::all();
            $conceptos = [];
            foreach ($tarifa as $tarifa) {
                $conceptos[] = [
                    "id" => $tarifa->id,
                    "id_tipo_toma" => $tarifa->id_tipo_toma,
                    "id_concepto" => $tarifa->id_concepto,
                    "nombre_concepto" => $tarifa->concepto->nombre,
                    "monto" => $tarifa->monto,
                ];
            }
            return json_encode($conceptos);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error en la busqueda.'
            ], 500);
        }
        
    }

    public function get_servicios_detalles_by_tarifa_idService($tarifa_id)
    {
        //fix to do
        try {
            $tarifa = Tarifa::findOrFail($tarifa_id);
            $servicio = [];
              foreach ($tarifa->servicio as $servicios) {
                 $servicio[] = [
                 "id" => $servicios->id,
                 "id_tarifa_servicio" => $servicios->id_tarifa_servicio,
                 "rango" => $servicios->rango,
                 "monto" => $servicios->monto,
                 /*"agua" => $servicios->agua,
                 "alcantarillado" => $servicios->alcantarillado,
                 "saneamiento" => $servicios->saneamiento,
                 */
               ];
           }

        usort($servicio, function ($a, $b) {
            return $a['rango'] <=> $b['rango'];
        });
        return json_encode($servicio);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error en la busqueda. ' .$ex
            ], 500);
        }
        
    }


}