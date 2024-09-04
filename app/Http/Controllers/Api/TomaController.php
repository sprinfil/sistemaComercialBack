<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedidorRequest;
use App\Models\Toma;
use App\Http\Requests\StoreTomaRequest;
use App\Http\Requests\UpdateMedidorRequest;
use App\Http\Requests\UpdateTomaRequest;
use App\Http\Resources\CargoResource;
use App\Http\Resources\MedidorResource;
use App\Http\Resources\OrdenTrabajoResource;
use App\Http\Resources\PagoResource;
use App\Http\Resources\TomaResource;
use App\Models\Medidor;
use App\Models\OrdenTrabajo;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Objects\Point;

class TomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response( TomaResource::collection(
            Toma::all()
        ),200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTomaRequest $request)
    {
        try{
            //VALIDA EL STORE
             $data = $request->validated();
             $toma = Toma::create($data);
        return response(new TomaResource ($toma), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la toma'.$e
            ], 500);
        }
         
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $toma = Toma::findOrFail($id);
            return response(new TomaResource($toma), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la toma'
            ], 500);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTomaRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $toma = Toma::findOrFail($id);
            $toma->update($data);
            $toma->save();
            return response(new TomaResource($toma), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la toma'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $toma = Toma::findOrFail($id);
            $toma->delete();
            return response("Toma eliminada con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo eliminar la toma'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function buscarCodigoToma($codigo)
    {
        try {
            $toma = Toma::where('codigo_toma', $codigo)->with("usuario")->first();
            return response(new TomaResource($toma), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la toma'
            ], 500);
        }
        //
    }
    public function buscarCodigoTomas($codigo)
    {
        try {
            $toma = Toma::where('codigo_toma', $codigo)->get();
            return response(TomaResource::collection($toma), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la toma'
            ], 500);
        }
        //
    }

    /**
     * Cargos por toma
     */
    public function cargosPorToma($codigo_toma)
    {
        try {
            $toma = Toma::where("codigo_toma", $codigo_toma)->first();
            // Ordena los cargos por el atributo 'prioridad' del concepto asociado
            $cargosOrdenados = $toma->cargos()->with('concepto')->get()->sortBy(function($cargo) {
                return $cargo->concepto->prioridad_abono;
            });
            return CargoResource::collection($cargosOrdenados);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Pagos por toma
     */
    public function pagosPorToma($id)
    {
        try {
            $toma = Toma::where("codigo_toma",$id)->first();
            return PagoResource::collection($toma->pagos);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Error al consultar los pagos'
            ], 500);
        }
    }

    /**
     * guardar posicion
     */

     public function save_position(Request $request, $toma_id){
        $data = $request["data"];
        $point = new Point($data["latitud"], $data["longitud"]);
        $toma = Toma::find($toma_id);
        $toma->posicion = $point;
        $toma->save();
    }
    
    
    public function ordenesToma($id)
    {
        try {
            $toma = Toma::where('codigo_toma',$id)->first();
            //$ordenes=$toma->ordenesTrabajo;
            //return OrdenTrabajoResource::collection($ordenes);
            $ordenes=OrdenTrabajo::where('id_toma', $toma['id'])->with(['toma.tipoToma','ordenTrabajoCatalogo.ordenTrabajoAccion','empleadoAsigno','empleadoEncargado','cargos'])->orderBy('created_at','desc')->paginate(20);
           return OrdenTrabajoResource::collection($ordenes); 
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Error al consultar las ordenes de trabajo'
            ], 500);
        }
    }
    public function general($id)
    {
        try {
            $toma = (new UsuarioService())->ConsultaGeneralToma($id);
            return $toma;
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Error al consultar las ordenes de trabajo'
            ], 500);
        }
    }

    public function registrarNuevoMedidor(StoreMedidorRequest $request){
        
        try {
            $data = $request->validated();
            //Toma::findOrFail($data['id_toma'])->desactivarMedidoresActivos();
            //$data['estatus'] = 'activo';
            $medidorActivo = Toma::findOrFail($data['id_toma'])->medidorActivo;
            if($medidorActivo && $data['estatus']=='activo'){
                Toma::findOrFail($data['id_toma'])->desactivarMedidoresActivos();
            }
            
            $medidor = Medidor::create($data);
            return response(new MedidorResource($medidor), 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el medidor para la toma '
            ], 500);
        }
    }

    /**
    * Display the specified resource.
    */
    public function medidorActivoPorToma($id)
    {
        try {
            $medidor = Toma::findOrFail($id)->medidorActivo;
            return response(new MedidorResource($medidor), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el medidor para la toma '.$id
            ], 500);
        }
    }

    /**
    * Display the specified resource.
    */
    public function medidoresPorToma($id)
    {
        try {
            return response(MedidorResource::collection(
                Toma::findOrFail($id)->medidores
            ),200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar los medidores '.$id
            ], 500);
        }
    }
}
