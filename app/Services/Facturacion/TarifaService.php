<?php
namespace App\Services\Facturacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTarifaConceptoDetalleRequest;
use App\Models\tarifa;
use Illuminate\Http\Request;
use App\Http\Requests\StoretarifaRequest;
use App\Http\Requests\StoreTarifaServicioRequest;
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
            $estado = $request->input('estado');
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
                ], 400);
            }
            //Si no existe la tarifa, crea una tarifa
            if (!$tarifa) {
                /* 
                Verifica el nombre de la tarifa con los datos de Data, esto
                para que no se agreguen tarifas con el mismo nombre ya registrado
                */
                $tarifaExistente = Tarifa::whereRaw('LOWER(nombre) = ?', 
                [strtolower($data['nombre'])])->first();
                if ($tarifaExistente) {
                    return response()->json([
                        'error' => 'El nombre de la tarifa ya existe',
                    ], 400);
                }
                $tarifa = Tarifa::create($data);
                if ($estado == 'activo') {
                    $tarifa->estado = 'activo';
                    $tarifa->save();
                     //La tarifa nueva, si tiene el estado activo, la activa y desactiva todas las demas
                    Tarifa::where('id', '!=', $tarifa->id)
                    ->update(['estado' => 'inactivo']);
                }
                // E importa las tarifas de la tarifa activa si se desea
                $request = new Request();
                $request->merge(['confirm' => true]);
                //$request->merge(['tarifa_id' => $tarifa->id]);
                $respuesta = $this->importarTipoTomaTarifas($request);
                return response($respuesta, 201); //201 created
            }
        } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar la tarifa.'
             ], 500);
        }
    }

    public function importarTipoTomaTarifas(Request $request){
        try{
            //input para importar una tarifa activa
            //$id_tarifa_nueva = $request->input('tarifa_id');
            $confirm = $request->input('confirm');
            //Si el Confirm del request es true y la tarifa_id es diferente de null entra a la condicion
            if($confirm){
                //consulta de los tipos de toma, con que servicio de tarifa tiene el tipo de la toma.
                $tarifas_tipo = TipoToma::with('tarifaServicio')->get();

                //Busca la tarifa que tenga el estado = activo
                $tarifa_activa = Tarifa::where('estado', 'activo')->first();
                $tarifa_nueva = Tarifa::orderBy('id' , 'desc')->first();
                //Detalles de las tarifas con cada servicio que tiene.
                $detalles = TarifaServiciosDetalle::with('tarifaServicio')->get();
               
                foreach ($tarifas_tipo as $tipo) { 
                    // y al final los servicios por tipo de toma, en la tarifa activa       
                    foreach ($tipo->tarifaServicio as $servicio) {
                        $existen = TarifaServicio::where('id_tarifa' , $tarifa_nueva->id)
                        ->where('id_tipo_toma', $tipo->id)
                        ->where('genera_iva' , $servicio['genera_iva'])
                        ->where('tipo_servicio' , $servicio['tipo_servicio'])
                        ->first();
                        if (!$existen) {
                                $ServiciosTarifa = new TarifaServicio();
                                $ServiciosTarifa->id_tarifa = $tarifa_nueva->id;
                                $ServiciosTarifa->id_tipo_toma = $tipo->id;
                                $ServiciosTarifa->genera_iva = $servicio['genera_iva'];
                                $ServiciosTarifa->tipo_servicio = $servicio['tipo_servicio'];
                                $ServiciosTarifa->save();
                        }
                        else{
                            $ServiciosTarifa = $existen;
                        }
                        foreach ($detalles as $detalle) {
                            $existeDetalle = TarifaServiciosDetalle::where('id_tarifa_servicio', $ServiciosTarifa->id)
                            ->where('rango', $detalle['rango'])
                            ->first();
                            if (!$existeDetalle) {
                                $detalle_servicio = new TarifaServiciosDetalle();
                                $detalle_servicio->id_tarifa_servicio = $ServiciosTarifa->id;
                                $detalle_servicio->rango = $detalle['rango'];
                                $detalle_servicio->monto = $detalle['monto'];
                                $detalle_servicio->save();
                            }
                        }
                    }
                 
                        

                    // valida si hay tarifas para ese tipo de toma
                    if(count($tipo->tarifaServicio) < 1){
                        return response()->json([
                            'error' => 'No hay servicios importables de tipo '.$tipo->id,
                            'import' => false
                        ], 500);
                    }
                }
                return response()->json([
                    'message' => 'Se han importado las tarifas',
                    'id_tarifa' => $request->input('confirm')
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
            $mismonombre = Tarifa::where('id' , '!=' , $tarifa->id)->get()
            ->filter(function ($exists) use ($data){
                return strcasecmp($exists->nombre , $data['nombre']) == 0;
            })
            ->isNotempty();
            if ($mismonombre) {
                return response()->json(['error' => 'Ya existe una tarifa con el mismo nombre'], 400);
            }
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
    public function storeTarifaServicioDetalle(array $tarifaDetalles) 
    {
        try {
        /* 
            
    Store de TarifasServiciosDetalle

	    •Cuando haces el Store de una tarifa_detalle_servicio, no puedes crear un registro de un servicio (una id_tarifa_servicio)
	     que no exista. 
	    •El rango no puede ser menor de 17 m³ y el monto no puede ser menor


    Update de TarifaServiciosDetalle

	    •Cuando haces el update de una tarifa_detalle_servicio, no puede haber 2 registros con el mismo id_tarifa_servicio, rango
	
	    •El monto cuando se hace el update, no tiene que ser menor
        
        */


            //Verifica que exista un servicio para poder añadir una tarfa servicio detalle
            $tarifaServicio = TarifaServicio::where('id' , $tarifaDetalles['id_tarifa_servicio'])->first();
            $rangomin = $tarifaServicio->tarifaDetalle->min('rango');
            $montomin = $tarifaServicio->tarifaDetalle->min('monto');
             //Si existe una tarifa servicio, hace el store
            if ($tarifaServicio->tarifaDetalle) {
                foreach ($tarifaServicio->tarifaDetalle as $detalle) {
                    if ($tarifaDetalles['monto'] <= $montomin) {
                        return response()->json(['error' => 'El monto no puede ser igual o menor que el monto minimo'], 400);
                    }
                    if ($tarifaDetalles['rango'] == $detalle->rango) {
                        return response()->json(['error' => 'El rango ingresado ya existe'] , 400);
                    }
                    elseif ($tarifaDetalles['rango'] < $rangomin) {
                        return response()->json(['error' => 'El rango no puede ser menor al rango minimo registrado'] , 400);
                    }
                }
                $tarifaServicioDetalle = TarifaServiciosDetalle::create($tarifaDetalles);
                return response(new TarifaServiciosDetalleResource($tarifaServicioDetalle), 201);
            }
            else{
                return response()->json([
                    'message' => 'El servicio de la tarifa no existe'
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo guardar el servicio detalle. ' .$ex
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

    public function updateTarifaServicioDetalleService(array $tarifaDetalles , $id)
    {
        try {
          
            $tarifaServicioDetalle = TarifaServiciosDetalle::findOrFail($id);
            $tarifaServicio = TarifaServicio::findOrFail($tarifaServicioDetalle->id_tarifa_servicio);
            $rangoActual = $tarifaServicioDetalle->rango;
            $montoActual = $tarifaServicioDetalle->monto;

            if (isset($tarifaDetalles['id_tarifa_servicio']) && $tarifaDetalles['id_tarifa_servicio'] != $tarifaServicioDetalle->id_tarifa_servicio) {
                $newtarifaServicio = TarifaServicio::find($tarifaDetalles['id_tarifa_servicio']);

                if (!$newtarifaServicio || $newtarifaServicio->tipo_servicio != $tarifaServicio->tipo_servicio) {
                    return response()->json(['error' => 'No se puede cambiar el id_tarifa_servicio a otro tipo de servicio'], 400);
                }
            }            
            $siguienteDetalle = TarifaServiciosDetalle::where('id_tarifa_servicio', $tarifaServicioDetalle->id_tarifa_servicio)
                ->where('rango', '>', $rangoActual)
                ->orderBy('rango', 'asc')
                ->first();
            $anteriorDetalle = TarifaServiciosDetalle::where('id_tarifa_servicio', $tarifaServicioDetalle->id_tarifa_servicio)
                ->where('rango', '<', $rangoActual)
                ->orderBy('rango', 'desc')
                ->first();
            $rangoExiste = TarifaServiciosDetalle::where('id_tarifa_servicio', $tarifaServicioDetalle->id_tarifa_servicio)
                ->where('rango', $tarifaDetalles['rango'])
                ->where('id', '!=', $tarifaServicioDetalle->id)
                ->first();
            
                if ($rangoExiste) {
                    return response()->json(['error' => 'El rango ingresado ya existe para esta tarifa'], 400);
                }
                
                // Verificar que el nuevo rango no sea menor que el rango actual ni mayor que el siguiente
                if ($tarifaDetalles['rango'] < $rangoActual) {
                    return response()->json(['error' => 'El nuevo rango no puede ser menor que el actual'], 400);
                }
                if ($siguienteDetalle && $tarifaDetalles['rango'] >= $siguienteDetalle->rango) {
                    return response()->json(['error' => 'El nuevo rango no puede ser mayor o igual que el siguiente rango'], 400);
                }
                
                // Validar que el nuevo monto esté entre los montos del rango anterior y siguiente
                if ($anteriorDetalle && $tarifaDetalles['monto'] <= $anteriorDetalle->monto) {
                    return response()->json(['error' => 'El nuevo monto no puede ser menor o igual al monto anterior'], 400);
                }
                if ($siguienteDetalle && $tarifaDetalles['monto'] >= $siguienteDetalle->monto) {
                    return response()->json(['error' => 'El nuevo monto no puede ser mayor o igual que el siguiente monto'], 400);
                }
            
            $tarifaServicioDetalle->update($tarifaDetalles);
            
            return response()->json(new TarifaServiciosDetalleResource($tarifaServicioDetalle), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se edito el servicio.' .$ex
            ], 500);
        }
    }

    public function get_conceptos_detalles_by_tarifa_idService($tarifa_id)
    {

        try {
            $tarifas = TarifaConceptoDetalle::with('tipoToma' ,
            'tipoToma.tarifaServicio' ,
            'tipoToma.tarifaServicio.tarifa')
            ->whereHas('tipoToma.tarifaServicio.tarifa', function($query) use ($tarifa_id){
                $query->where('id', $tarifa_id);
            })
            ->get();
            $conceptos = [];
            foreach ($tarifas as $tarifa) {
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
            $tarifa = TarifaServiciosDetalle::with('tarifaServicio', 'tarifaServicio.tarifa')
            ->whereHas('tarifaServicio.tarifa', function($query) use ($tarifa_id){
                $query->where('id', $tarifa_id);
            })
            ->get();
       //$tarifa = Tarifa::findOrFail($tarifa_id);
            $servicio = [];
              foreach ($tarifa as $servicios) {
                 $servicio[] = [
                 "id" => $servicios->id,
                 "id_tarifa_servicio" => $servicios->id_tarifa_servicio,
                 "rango" => $servicios->rango,
                 "monto" => $servicios->monto,
               ];
           }

        usort($servicio, function ($a, $b) {
            return $a['rango'] <=> $b['rango'];
        });
        if (!$servicio) {
            return response()->json(['error' => 'Hubo un error en la busqueda.'], 400);
        }
        else{
            return json_encode($servicio);
        }
            } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error en la busqueda. ' .$ex
            ], 500);
         }
        
    }


}