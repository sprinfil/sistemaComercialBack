<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTarifaConceptoDetalleRequest;
use App\Models\tarifa;
use App\Http\Requests\StoretarifaRequest;
use App\Http\Requests\StoreTarifaServiciosDetalleRequest;
use App\Http\Requests\UpdateTarifaConceptoDetalle;
use App\Http\Requests\UpdatetarifaRequest;
use App\Http\Requests\UpdateTarifaServiciosDetalleRequest;
use App\Http\Resources\StoreTarifaConceptoDetalleResource;
use App\Http\Resources\TarifaConceptoDetalleResource;
use App\Http\Resources\TarifaResource;
use App\Http\Resources\TarifaServiciosDetalleResource;
use App\Models\TarifaConceptoDetalle;
use App\Models\TarifaServiciosDetalle;
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
        //$this->authorize('create', Operador::class);
        return TarifaResource::collection(
            tarifa::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoretarifaRequest $request)
    {
        try{
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
        if(!$tarifa)
        {
            $tarifa = tarifa::create($data);
            return response(new tarifaResource ($tarifa), 201);
        }

        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la tarifa'
            ], 500);
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
    public function update(UpdatetarifaRequest $request,  string $id)
    {
        //$this->authorize('update', tarifa::class);
        //Log::info("id");
        try {
            $data = $request->validated();
            $tarifa = tarifa::findOrFail($id);
            $tarifa->update($data);
            $tarifa->save();
            return response(new tarifaResource($tarifa), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la tarifa'
            ], 500);
        }
            
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', tarifa::class);
        try {
            $operador = tarifa::findOrFail($id);
            $operador->delete();
            return response("Tarifa eliminada con exito",200);
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
        //$this->authorize('create', Operador::class);
        return TarifaConceptoDetalleResource::collection(
            TarifaConceptoDetalle::all()
        );
    }   
    public function storeTarifaConceptoDetalle(StoreTarifaConceptoDetalleRequest $request)
    {
       // $data = $request->validated();
        //return response()->json(['message' => $data], 200);
        try{
            //VALIDA EL STORE
            $data = $request->validated();
            $tarifaConceptoDetalle = TarifaConceptoDetalle::create($data);
            
            return response(new TarifaConceptoDetalleResource ($tarifaConceptoDetalle), 201);

        } catch(Exception $e) {
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
        //$this->authorize('update', tarifa::class);
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
        //$this->authorize('create', Operador::class);
        return TarifaServiciosDetalleResource::collection(
            TarifaServiciosDetalle::all()
        );
    }  

    public function storeTarifaServicioDetalle(StoreTarifaServiciosDetalleRequest $request)
    {
       // $data = $request->validated();
        //return response()->json(['message' => $data], 200);
        try{
            //VALIDA EL STORE
            $data = $request->validated();
            $tarifaServicioDetalle = TarifaServiciosDetalle::create($data);
            
            return response(new TarifaServiciosDetalleResource ($tarifaServicioDetalle), 201);

        } catch(Exception $e) {
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
    public function TarifasPorConcepto(UpdateTarifaServiciosDetalleRequest $request,  string $id)
    {
        //$this->authorize('update', tarifa::class);
        //Log::info("id");

        //Falta validacion que evite modificaciones si ya esta asociado a una facturacion
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
}
