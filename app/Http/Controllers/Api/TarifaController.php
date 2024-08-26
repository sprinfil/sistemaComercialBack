<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTarifaConceptoDetalleRequest;
use App\Models\Tarifa;
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
use App\Services\Facturacion\TarifaService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;

class TarifaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        ////$this->authorize('create', Operador::class);
        try {
            DB::beginTransaction();
            $tarifa = (new TarifaService())->indexTarifaService();
            DB::commit();
            return $tarifa;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se encontro registro de tarifas.'
            ], 500);
        }
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTarifaRequest $request)
    {
        try {
            //VALIDA EL STORE
            $data = $request->validated();
            $nombre = $request->nombre;
            DB::beginTransaction();
            $tarifa = (new TarifaService())->storeTarifaService($data, $nombre);
            DB::commit();
            return $tarifa;
            
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al guardar la tarifa'
            ], 500);
        }
    }

    public function importarTipoTomaTarifas(Request $request){
        try{
           DB::beginTransaction();
           $tarifa = (new TarifaService())->importarTipoTomaTarifas($request);
           DB::commit();
           return $tarifa;
        }catch(Exception $ex){
            DB::rollBack();
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
            DB::beginTransaction();
            $tarifaShow = (new TarifaService())->showTarifaService($tarifa);
            DB::commit();
            return $tarifaShow;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
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
            $data = $request->validated();
            $descripcion = $request->input('descripcion');
            $estado = $request->input('estado');
            DB::beginTransaction();
            $tarifa = (new TarifaService())->updateTarifaService($data, $id, $estado, $descripcion);
            DB::commit();
            return $tarifa;  
           
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo editar la tarifa' .$e
            ], 500);
        }
    }

    public function actualizarEstadoTarifa(Request $request)
    {
        try {
            DB::beginTransaction();
            $actTarifa = (new TarifaService())->actualizarEstadoTarifaService($request);
            DB::commit();
            return $actTarifa;
        } catch (Exception $e) {
            DB::rollBack();
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
            DB::beginTransaction();
           $tarifa = (new TarifaService())->destroyTarifaService($id);
           DB::commit();
           return $tarifa;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se ha removido la tarifa'
            ], 500);
        }
        //
    }

    public function restaurarTarifa(Tarifa $tarifa, HttpRequest $request)
    {
        try {
            $id = $request->id;
            DB::beginTransaction();
            $tarifa = (new TarifaService())->restaurarTarifaService($id);
            DB::commit();
            return $tarifa;
           } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se ha restaurado la tarifa.'
            ], 500);
           }
    }

    //METODOS DE TARIFA_CONCEPTO_DETALLE

    public function indexTarifaConceptoDetalle()
    {
        ////$this->authorize('create', Operador::class);

        try {
            DB::beginTransaction();
            $tarifaConcepto = (new TarifaService())->indexTarifaConceptoDetalleService();
            DB::commit();
            return $tarifaConcepto;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se han encontrado conceptos de la tarifa.'
            ], 500);
        }
    }

    public function storeTarifaConceptoDetalle(StoreTarifaConceptoDetalleRequest $request)
    {
        // $data = $request->validated();
        
        try {
            //VALIDA EL STORE
            $data = $request->validated();
            DB::beginTransaction();
            $tarifaConcepto = (new TarifaService())->storeTarifaConceptoDetalleService($data);
            DB::commit();
            return $tarifaConcepto;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se guardo el concepto detalle de tarifa.'
            ], 500);
        }
    }

    public function showTarifaConceptoDetalle($tarifaDetalle)
    {
        try {
            DB::beginTransaction();
           $tarifaConcepto = (new TarifaService())->showTarifaConceptoDetalleService($tarifaDetalle);
           DB::commit();
           return $tarifaConcepto;
        } catch (ModelNotFoundException $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se encontro el concepto asociado a la tarifa.'
            ], 500);
        }
        //
    }

    public function updateTarifaConceptoDetalle(UpdateTarifaConceptoDetalle $request,  string $id)
    {
        ////$this->authorize('update', tarifa::class);
  
        //Falta validacion que evite modificaciones si ya esta asociado a una facturacion
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $tarifaConcepto = (new TarifaService())->updateTarifaConceptoDetalleService($data,$id);
            DB::commit();
            return $tarifaConcepto;
          
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se edito el concepto de tarifa'
            ], 500);
        }
    }

    //Servicio tarifa detalle
    public function indexServicioDetalle()
    {
        ////$this->authorize('create', Operador::class);
        try {
            DB::beginTransaction();
            $tarifaServicioDetalle = (new TarifaService())->indexServicioDetalleservice();
            DB::commit();
            return $tarifaServicioDetalle;
        } catch (Exception $ex) {
            DB::rollBack();
            
        }
        return TarifaServiciosDetalleResource::collection(
            TarifaServiciosDetalle::all()
        );
    }

    public function storeTarifaServicioDetalle(StoreTarifaServiciosDetalleRequest $request)
    { 
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $storeTarifaServicioDetalle = (new TarifaService())->storeTarifaServicioDetalle($data);
            DB::commit();
            return $storeTarifaServicioDetalle;       
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el detalle de servicio ' .$e
            ], 500);
        }
    }

    public function showTarifaServicioDetalle($tarifaDetalle)
    {
        try {
           DB::beginTransaction();
           $tarifaServicioDetalle = (new TarifaService())->showTarifaServicioDetalleService($tarifaDetalle);
           DB::commit();
           return $tarifaServicioDetalle;
        } catch (ModelNotFoundException $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo encontrar el servicio asociado a la tarifa.'
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
            $id = $request->id;
            DB::beginTransaction();
            $tarifaServicioDetalle = (new TarifaService())->updateTarifaServicioDetalleService($data, $id);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se edito el servicio.'
            ], 500);
        }
    }

    public function get_conceptos_detalles_by_tarifa_id($tarifa_id)
    {
        try {
            DB::beginTransaction();
            $tarifaConcepto = (new TarifaService())->get_conceptos_detalles_by_tarifa_idService($tarifa_id);
            DB::commit();
            return $tarifaConcepto;
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error en la busqueda.'
            ], 500);
        }
    }

    public function get_servicios_detalles_by_tarifa_id($tarifa_id)
    {
        try {
            DB::beginTransaction();
            $tarifaServicioDetalle = (new TarifaService())->get_servicios_detalles_by_tarifa_idService($tarifa_id);
            DB::commit();
            return $tarifaServicioDetalle;
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error en la busqueda.'
            ], 500);
        }
    }
}
