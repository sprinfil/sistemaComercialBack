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
use App\Services\TomaService;
use App\Services\UsuarioService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            $toma = Toma::where('codigo_toma', $codigo)->with("usuario",'calle','entre_calle_2','entre_calle_1','colonia')->first();
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
            // Buscar la toma por su código
            $toma = Toma::where("codigo_toma", $id)->first();
            
            // Verificar si la toma existe
            if($toma) {
                // Cargar los pagos con los abonos relacionados
                $pagos = $toma->pagos()->with('abonosConCargos')->get();

                // Verificar si existen pagos
                if($pagos->isNotEmpty()) {
                    // Retornar los pagos utilizando el PagoResource para transformar los datos
                    return PagoResource::collection($pagos);
                } else {
                    // Si no hay pagos, retornar null o algún mensaje vacío
                    return response()->json(['message' => 'No se encontraron pagos para esta toma.'], 404);
                }
            } else {
                // Si la toma no existe, retornar un error 404
                return response()->json(['error' => 'Toma no encontrada'], 404);
            }
        } catch (ModelNotFoundException $e) {
            // Capturar cualquier excepción de modelo no encontrado y retornar un error
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
            $ordenes=OrdenTrabajo::where('id_toma', $toma['id'])->with(['toma.calle','toma.entre_calle_2','toma.entre_calle_1','toma.colonia','toma.tipoToma','toma.ruta','toma.libro','ordenTrabajoCatalogo.ordenTrabajoAccion','empleadoGenero','empleadoAsigno','empleadoEncargado','cargos'])->orderBy('created_at','desc')->paginate(20);
            return OrdenTrabajoResource::collection($ordenes); 
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Error al consultar las ordenes de trabajo'
            ], 500);
        }
    }
    public function ordenesTomaSinAsignadas($id)
    {
        try {
            $toma = Toma::where('codigo_toma',$id)->first();
            $hoy=Carbon::now('America/Denver')->startOfDay();
            $hoyFormateado = $hoy->format('Y-m-d H:i:s'); ///VOLVERLO UNIVERSAL
            $hoyFormateadofinal= $hoy->setTimezone('America/Denver')->endOfDay()->format('Y-m-d H:i:s');
            $ordenes=OrdenTrabajo::where('id_toma', $toma['id'])->with(['usuario','toma.tipoToma','toma.ruta','toma.libro','ordenTrabajoCatalogo.ordenTrabajoAccion','empleadoGenero','empleadoAsigno','empleadoEncargado','cargos'])->where('estado','!=' ,'En proceso')->whereBetween('created_at',[$hoyFormateado, $hoyFormateadofinal])->orderBy('created_at','desc')->paginate(20);
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
            $data['fecha_instalacion'] = Carbon::now();
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
            $medidores = Toma::findOrFail($id)
                ->medidores()
                ->orderByRaw("CASE WHEN estatus = 'activo' THEN 0 ELSE 1 END")
                ->orderBy('created_at', 'desc')
                ->get();
        
            return response(MedidorResource::collection($medidores), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar los medidores ' . $id
            ], 500);
        }
            
    }
    public function filtradoTomas(Request $request){
        try{
            DB::beginTransaction();
            //$filtros=$request->validated();
            $filtros=$request->all();
            $data=(new TomaService())->tomaTipos($filtros);
            //return $data;
            // return $data;
            if (!$data){
                return response()->json(["message"=>"No ha seleccionado un filtro para tomas, por favor especifique algún parametro"],500);
            }
            else
            {
                DB::commit();
                return response()->json(['tomas'=>TomaResource::collection($data)]);
                //return response()->json(['tomas'=>$data]);
                //return response()->json(["Orden de trabajo"=>new OrdenTrabajoResource($data[0]),"Cargos"=>CargoResource::collection($data[1])],200);
            }
           }
           catch(Exception $ex){
            DB::rollBack();
            return response()->json(["error"=>"No se pudo consultar tomas ".$ex],500);
           }
    }
}
