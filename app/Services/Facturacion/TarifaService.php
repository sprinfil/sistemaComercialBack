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
            tarifa::all()
        );
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron no se encontraron registros de tarifas.'
        ], 200);
       }
        

    }

    public function storeTarifaService(array $data, string $nombre)
    {
        try {       
             //Busca por nombre las tarifas eliminadas
            $tarifa = tarifa::withTrashed()->where('nombre', $nombre)->first();

            //VALIDACION POR SI EXISTE
            if ($tarifa) {
                if ($tarifa->trashed()) {
                    return response()->json([
                        'message' => 'La tarifa ya existe pero ha sido eliminada. ¿Desea restaurarla?',
                        'restore' => true,
                        'tarifa' => $tarifa->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'La tarifa ya existe.',
                    'restore' => false
                ], 200);
            }
            //Si no existe la tarifa, crea una tarifa
            if (!$tarifa) {
                $tarifa = tarifa::create($data);
                // E importa las tarifas de la tarifa activa si se desea
                $request = new Request();
                $request->merge(['confirm' => true]);
                $request->merge(['tarifa_id' => $tarifa->id]);
                $respuesta = $this->importarTipoTomaTarifas($request);
                return response($respuesta, 201);
            }
        } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar la tarifa.'
             ], 200);
        }              
    }

    public function importarTipoTomaTarifas(Request $request){
        try{
            if($request->input('confirm') == true && $request->input('tarifa_id') != null){
                $id_tarifa_nueva = $request->input('tarifa_id');
                // se obtienen los conceptos importados de conceptos por tipo de toma
                // primero los tipos de toma
                $tarifas_tipo = TipoToma::all();
                $tarifa_activa = tarifa::where('estado', 'activo')->get()->first();
                foreach ($tarifas_tipo as $tipo) {
                    

                    // y al final los servicios por tipo de toma, en la tarifa activa
                    $tarifas_servicios = TarifaServiciosDetalle::where('id_tipo_toma', $tipo->id)
                    ->where('id_tarifa', $tarifa_activa->id)->get();

                    foreach ($tarifas_servicios as $servicio) {
                        $detalle_servicio = new TarifaServiciosDetalle();
                        $detalle_servicio->id_tarifa = $id_tarifa_nueva;
                        $detalle_servicio->id_tipo_toma = $tipo->id;
                        $detalle_servicio->rango = $servicio['rango'];
                        $detalle_servicio->agua = $servicio['agua'];
                        $detalle_servicio->alcantarillado = $servicio['alcantarillado'];
                        $detalle_servicio->saneamiento = $servicio['saneamiento'];
                        $detalle_servicio->save();
                    }

                    // valida si hay tarifas para ese tipo de toma
                    if(count($tarifas_servicios) < 1){
                        return response()->json([
                            'error' => 'No hay servicios importables de tipo '.$tipo->id,
                            'import' => false
                        ], 200);
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
            ], 200);
        }
    }

    public function showTarifaService($tarifa)
    {

        try {
            $tarifa = tarifa::findOrFail($tarifa);
            return response(new tarifaResource($tarifa), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la tarifa'
            ], 500);
        }
        //

    }

    

    public function updateTarifaService(array $data, string $id, string $estado)
    {
               
        try {            
              
            $catalogoTiposToma = TipoToma::select('id', 'nombre')->get();
            $catalogoServicioDealle = TarifaServiciosDetalle::select('id_tipo_toma', 'rango')->where('id_tarifa', $id)->get();
            
            $tarifa = tarifa::findOrFail($id);
            //$totalConcepto = count($catalogoConcepto);
            $servicioAsociado = false;
            //return $catalogoServicioDealle;
            if ($tarifa) {
                if ($tarifa->estado == 'inactivo' && $estado == 'activo') {

                    foreach ($catalogoTiposToma as $TipoToma) {

                        foreach ($catalogoServicioDealle as $servicioDetalle) {
    
                            if ($TipoToma->id == $servicioDetalle->id_tipo_toma) {
                                $servicioAsociado = true;
                            }
                        }
    
                        if ($servicioAsociado == false) {
                            return response()->json([
                                'error' => 'No se activo la tarifa, existen tomas sin servicio asociado'
                            ], 400);
                        } else {
                            $servicioAsociado = false;
                        }
                    }

                    return response()->json([
                        'message' => 'Existen tarifas anteriores activas. ¿Desea inactivarlas?',
                        'confirmUpdate' => true,
                    ], 200);
                }
                //valida que exista almenos 1 rango de servicio asociado a la tarifa a activar
              
                $tarifa->update($data);
                $tarifa->save();
                return response(new tarifaResource($tarifa), 200);  
              
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo editar la tarifa'
            ], 400);
        }        
              
    }

    public function actualizarEstadoTarifaService(Request $request)
    {
        try {
            //obtenemos la respuesta
            if ($request->input('confirmUpdate')) {
                // inactivar todo TODO
                tarifa::where('estado', 'activo')->update(['estado' => 'inactivo']);
                
                // obtener el fakin id
                $tarifaId = $request->input('tarifa_id');
                $tarifa = tarifa::find($tarifaId);
                if ($tarifa) {
                    $tarifa->estado = 'activo';
                    $tarifa->save();
                }
    
                return response()->json([
                    'message' => 'Actualización realizada con éxito.',
                ], 200);
            }
            return response()->json([
                'message' => 'No se realizó ninguna actualización.',
            ], 200);
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
            $tarifa = tarifa::findOrFail($id);
            $tarifa->delete();
            return response("Tarifa eliminada con exito", 200);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'error' => 'No se ha removido la tarifa'
            ], 500);
        }
        
    }

    public function restaurarTarifaService(string $id)
    {

        try {
            $tarifa = tarifa::withTrashed()->findOrFail($id);

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

        try {
            $tarifa = Tarifa::find($tarifa_id);
            $servicio = [];
              foreach ($tarifa->servicio as $servicios) {
                 $servicio[] = [
                 "id" => $servicios->id,
                 "id_tarifa" => $servicios->id_tarifa,
                 "id_tipo_toma" => $servicios->id_tipo_toma,
                 "rango" => $servicios->rango,
                 "agua" => $servicios->agua,
                 "alcantarillado" => $servicios->alcantarillado,
                 "saneamiento" => $servicios->saneamiento,
               ];
           }

        usort($servicio, function ($a, $b) {
            return $a['rango'] <=> $b['rango'];
        });
        return json_encode($servicio);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error en la busqueda.'
            ], 500);
        }
        
    }


}