<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTarifaConceptoDetalleRequest;
use App\Models\tarifa;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTarifaRequest;
use App\Http\Requests\StoreTarifaServiciosDetalleRequest;
use App\Http\Requests\UpdateTarifaConceptoDetalle;
use App\Http\Requests\UpdateTarifaRequest;
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


class TarifaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        ////$this->authorize('create', Operador::class);
        return TarifaResource::collection(
            tarifa::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTarifaRequest $request)
    {
        try {
            //VALIDA EL STORE
            $data = $request->validated();
            //Busca por nombre las tarifas eliminadas
            $tarifa = tarifa::withTrashed()->where('nombre', $request->input('nombre'))->first();

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
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la tarifa'
            ], 500);
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
                    // despues los conceptos por tipo de toma
                    /*$tarifas_conceptos = TarifaConceptoDetalle::where('id_tipo_toma', $tipo)->get();

                    foreach ($tarifas_conceptos as $concepto) {
                        $nuevo_concepto = new TarifaConceptoDetalle;
                        $nuevo_concepto->id_tipo_toma = $tipo;
                        $nuevo_concepto->id_concepto = $concepto['id_concepto'];
                        $nuevo_concepto->monto = $concepto['monto'];
                        $nuevo_concepto->save();
                    }

                    // valida si hay tarifas para ese tipo de toma
                    $tarifa_registrada =  $request->input('id');
                    if(count($tarifas_conceptos) < 1){
                        return response()->json([
                            'error' => 'No hay conceptos importables de tipo '.$tipo,
                            'import' => false
                        ], 200);
                    }*/

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

    /**
     * Display the specified resource.
     */
    public function show($tarifa)
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTarifaRequest $request,  string $id)
    {
        ////$this->authorize('update', tarifa::class);

        try {
            //$catalogoConcepto = ConceptoCatalogo::select('id','nombre','estado')->where('estado',"activo")->get();
            //$tarifaDetalle = TarifaConceptoDetalle::select('id_tarifa','id_concepto','monto')->where('id_tarifa',$id)->get();
            $catalogoTiposToma = TipoToma::select('id', 'nombre')->get();
            $catalogoServicioDealle = TarifaServiciosDetalle::select('id_tipo_toma', 'rango')->where('id_tarifa', $id)->get();
            $data = $request->validated();
            $tarifa = tarifa::findOrFail($id);
            //$totalConcepto = count($catalogoConcepto);
            $servicioAsociado = false;
            //return $catalogoServicioDealle;
            if ($tarifa) {
                if ($tarifa->estado == 'inactivo' && $request->input('estado') == 'activo') {

                    foreach ($catalogoTiposToma as $TipoToma) {

                        foreach ($catalogoServicioDealle as $servicioDetalle) {
    
                            if ($TipoToma->id == $servicioDetalle->id_tipo_toma) {
                                $servicioAsociado = true;
                            }
                        }
    
                        if ($servicioAsociado == false) {
                            return response()->json([
                                'error' => 'No se pudo activar la tarifa, existen tomas sin servicio asociado'
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
            return response()->json([
                'error' => 'No se pudo editar la tarifa'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la tarifa' . $e
            ], 500);
        }
    }

    public function actualizarEstadoTarifa(Request $request)
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
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la tarifa: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //$this->authorize('delete', tarifa::class);
        try {
            $operador = tarifa::findOrFail($id);
            $operador->delete();
            return response("Tarifa eliminada con exito", 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se ha podido remover la tarifa'
            ], 500);
        }
        //
    }

    public function restaurarTarifa(tarifa $tarifa, HttpRequest $request)
    {
        $tarifa = tarifa::withTrashed()->findOrFail($request->id);

        // Verifica si el registro está eliminado
        if ($tarifa->trashed()) {

            // Restaura el registro
            $tarifa->restore();
            return response()->json(['message' => 'La tarifa ha sido restaurada.'], 200);
        }
    }

    //METODOS DE TARIFA_CONCEPTO_DETALLE

    public function indexTarifaConceptoDetalle()
    {
        ////$this->authorize('create', Operador::class);
        return TarifaConceptoDetalleResource::collection(
            TarifaConceptoDetalle::all()
        );
    }

    public function storeTarifaConceptoDetalle(StoreTarifaConceptoDetalleRequest $request)
    {
        // $data = $request->validated();
        //return response()->json(['message' => $data], 200);
        try {
            //VALIDA EL STORE
            $data = $request->validated();
            $tarifaConceptoDetalle = TarifaConceptoDetalle::create($data);

            return response(new TarifaConceptoDetalleResource($tarifaConceptoDetalle), 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el concepto detalle de tarifa'
            ], 500);
        }
    }

    public function showTarifaConceptoDetalle($tarifaDetalle)
    {
        try {
            $tarifaDetalle = TarifaConceptoDetalle::findOrFail($tarifaDetalle);
            return response(new TarifaConceptoDetalleResource($tarifaDetalle), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la el concepto asociado a la tarifa'
            ], 500);
        }
        //
    }

    public function updateTarifaConceptoDetalle(UpdateTarifaConceptoDetalle $request,  string $id)
    {
        ////$this->authorize('update', tarifa::class);
        //Log::info("id");

        //Falta validacion que evite modificaciones si ya esta asociado a una facturacion
        try {
            $data = $request->validated();
            $tarifaConcepto = TarifaConceptoDetalle::findOrFail($request["id"]);
            $tarifaConcepto->update($data);
            $tarifaConcepto->save();
            return response(new TarifaConceptoDetalleResource($tarifaConcepto), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el concepto de tarifa'
            ], 500);
        }
    }

    //Servicio tarifa detalle
    public function indexServicioDetalle()
    {
        ////$this->authorize('create', Operador::class);
        return TarifaServiciosDetalleResource::collection(
            TarifaServiciosDetalle::all()
        );
    }

    public function storeTarifaServicioDetalle(StoreTarifaServiciosDetalleRequest $request)
    {
        /*
               try{
            
            $registro = TarifaServiciosDetalle::select('rango','agua','alcantarillado','saneamiento')->where('id_tarifa',$request->id_tarifa)->orderBy('rango')->get();
            //return $registro;
            //next $rangoRegistrado
            foreach ($registro as $rangoRegistrado) {

              if ($rangoRegistrado->rango == $request->rango) {
                return response()->json([
                    'error' => 'No se puede repetir el rango en la misma tarifa'
                ], 500);
              }

              else{
                 //VALIDA EL STORE
                 $data = $request->validated();
                 $tarifaServicioDetalle = TarifaServiciosDetalle::create($data);
                 return response(new TarifaServiciosDetalleResource ($tarifaServicioDetalle), 201);
                }
            }
            
           
        }catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el detalle de servicio'
            ], 500);
        }
       */
        try {

            //VALIDA EL STORE
            $data = $request->validated();
            $tarifaServicioDetalle = TarifaServiciosDetalle::create($data);
            return response(new TarifaServiciosDetalleResource($tarifaServicioDetalle), 201);
            
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el detalle de servicio'
            ], 500);
        }
    }

    public function showTarifaServicioDetalle($tarifaDetalle)
    {
        try {
            $tarifaDetalle = TarifaServiciosDetalle::findOrFail($tarifaDetalle);
            return response(new TarifaServiciosDetalleResource($tarifaDetalle), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el servicio asociado a la tarifa'
            ], 500);
        }
    }

    // Consultas especificas
    public function TarifasPorConcepto(UpdateTarifaServiciosDetalleRequest $request,  string $id)
    {
        //$this->authorize('update', tarifa::class);
        //Log::info("id");
    }

    public function updateTarifaServicioDetalle(UpdateTarifaServiciosDetalleRequest $request,  string $id)
    {
        try {
            $data = $request->validated();
            $tarifaServicioDetalle = TarifaServiciosDetalle::findOrFail($request["id"]);
            $tarifaServicioDetalle->update($data);
            $tarifaServicioDetalle->save();
            return response(new TarifaServiciosDetalleResource($tarifaServicioDetalle), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el servicio'
            ], 500);
        }
    }

    public function get_conceptos_detalles_by_tarifa_id($tarifa_id)
    {
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
    }

    public function get_servicios_detalles_by_tarifa_id($tarifa_id)
    {
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
    }
}
