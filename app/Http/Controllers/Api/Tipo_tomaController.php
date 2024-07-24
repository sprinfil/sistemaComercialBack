<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoToma;
use App\Http\Requests\StoreTipoTomaRequest;
use App\Http\Requests\UpdateTipoTomaRequest;
use App\Http\Resources\TipoTomaResource;
use App\Models\tarifa;
use App\Models\TarifaConceptoDetalle;
use App\Models\TarifaServiciosDetalle;
use Database\Factories\TarifaConceptoDetalleFactory;
use Exception;
use Illuminate\Http\Request;

class Tipo_tomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$this->authorize('viewAny', TipoToma::class);
        try{
            return TipoTomaResource::collection(
                TipoToma::all()
            );
        }
        catch(Exception $ex){
            return response()->json(['message' => 'No se encontro el tipo de toma'], 200);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoTomaRequest $request)
    {
        //$this->authorize('create', TipoToma::class);
        try{
            $data=$request->validated();
            $Tipotoma = TipoToma::withTrashed()->where('nombre',$request['nombre'])->first();

            //VALIDACION POR SI EXISTE
            if ($Tipotoma) {
                if ($Tipotoma->trashed()) {
                    return response()->json([
                        'message' => 'El el tipo de toma ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                        'restore' => true,
                        'tipoToma_id' => $Tipotoma->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'El tipo de toma ya existe.',
                    'restore' => false
                ], 200);
            }
            //si no existe el tipo de toma lo crea
            if(!$Tipotoma)
            {
                $Tipotoma=TipoToma::create($data);
                /*return response()->json([
                    'message' => 'No existen tarifas para este tipo de toma. ¿Desea importarlas?',
                    'confirm' => true,
                ], 200);*/
                 // E importa las tarifas de un tipo de toma de la tarifa activa si se desea
                 $request = new Request();
                 $request->merge(['confirm' => true]);
                 $request->merge(['tipo' => 4]);
                 $request->merge(['id' => $Tipotoma->id]);

                 $respuesta = $this->importarTipoTomaTarifas($request);
                 return response($respuesta, 201);
            }
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'El tipo de toma no se pudo crear.',
                'restore' => false
            ], 200);
        }
    }

    public function importarTipoTomaTarifas(Request $request){
        try{
            if($request->input('confirm') == true){
                $tarifa_selecionada = $request->input('tipo');
                $tarifa = 0;
                if($tarifa_selecionada){ // toma el tipo de toma del que se van a importar las tarifas
                    $tarifa = TipoToma::find($tarifa_selecionada)->id;
                }
                // se obtienen los conceptos importados

                // de conceptos
                $tarifas_conceptos = TarifaConceptoDetalle::where('id_tipo_toma', $tarifa)
                ->get();

                // y de servicios
                $tarifa_activa = tarifa::where('estado', 'activo')->get()->first();
                // se consulta la tarifa activa para obtener los servicios
                $tarifas_servicios = TarifaServiciosDetalle::where('id_tipo_toma', $tarifa)
                ->where('id_tarifa', $tarifa_activa->id)
                ->get();

                // y se registran ambas tarifas para el nuevo tipo de toma
                $tarifa_registrada =  $request->input('id');
                if(count($tarifas_conceptos) < 1){
                    return response()->json([
                        'error' => 'No hay conceptos importables',
                        'import' => false
                    ], 200);
                }
                foreach ($tarifas_conceptos as $concepto) {
                    $detalle_concepto = new TarifaConceptoDetalle;
                    $detalle_concepto->id_tipo_toma = $tarifa_registrada;
                    $detalle_concepto->id_concepto = $concepto['id_concepto'];
                    $detalle_concepto->monto = $concepto['monto'];
                    $detalle_concepto->save();
                }

                if(count($tarifas_servicios) < 1){
                    return response()->json([
                        'error' => 'No hay servicios importables',
                        'import' => false
                    ], 200);
                }
                foreach ($tarifas_servicios as $servicio) {
                    $detalle_servicio = new TarifaServiciosDetalle();
                    $detalle_servicio->id_tarifa = $tarifa_activa->id;
                    $detalle_servicio->id_tipo_toma = $tarifa_registrada;
                    $detalle_servicio->rango = $servicio['rango'];
                    $detalle_servicio->agua = $servicio['agua'];
                    $detalle_servicio->alcantarillado = $servicio['alcantarillado'];
                    $detalle_servicio->saneamiento = $servicio['saneamiento'];
                    $detalle_servicio->save();
                }
                
                return response()->json([
                    'message' => 'Se importaron los registros ',
                    'import' => $request->input('confirm')
                ], 200);
            }else{
                // si no se desea importar, crea unas tarifas en blanco
                $tarifa_activa = tarifa::where('estado', 'activo')->get()->first();

                $tarifas_conceptos = TarifaConceptoDetalle::where('id_tipo_toma', 1)
                ->get();

                $tarifas_servicios = TarifaServiciosDetalle::where('id_tipo_toma', 1)
                ->where('id_tarifa', $tarifa_activa->id)
                ->get();

                $tarifa_registrada =  $request->input('id');

                foreach ($tarifas_conceptos as $concepto) {
                    $detalle_concepto = new TarifaConceptoDetalle;
                    $detalle_concepto->id_tipo_toma = $tarifa_registrada;
                    $detalle_concepto->id_concepto = $concepto['id_concepto'];
                    $detalle_concepto->monto = 0;
                    $detalle_concepto->save();
                }

                foreach ($tarifas_servicios as $servicio) {
                    $detalle_servicio = new TarifaServiciosDetalle();
                    $detalle_servicio->id_tarifa = $tarifa_activa->id;
                    $detalle_servicio->id_tipo_toma = $tarifa_registrada;
                    $detalle_servicio->rango = $servicio['rango'];
                    $detalle_servicio->agua = 10;
                    $detalle_servicio->alcantarillado = 2;
                    $detalle_servicio->saneamiento = 2;
                    $detalle_servicio->save();
                }

                return response()->json([
                    'message' => 'Registros en blanco '.$request->input('tipo').' '.$request->input('id'),
                    'import' => $request->input('confirm')
                ], 200);
            }
            return response("",201);
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
    public function show(string $tipoToma)
    {
        try{
            $data = TipoToma::ConsultarPorNombre($tipoToma);
            return TipoTomaResource::collection(
                $data
            );
        }
        catch(Exception $Ex){
            return response()->json(['message' => 'No se encontro el tipo de toma'], 200);

        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoTomaRequest $request, TipoToma $tipoToma)
    {
        //$this->authorize('update', TipoToma::class);
        try{
            $data=$request->validated();
            $usuario=TipoToma::findorFail($request['id']);
            $usuario->update($data);
            $usuario->save();
            return new TipoTomaResource($usuario);
        }
        catch(Exception $ex){
            throw new Exception($ex);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoToma $tipoToma,Request $request)
    {
        //$this->authorize('delete', TipoToma::class);
        try
        {
            $tipoToma = TipoToma::findOrFail($request["id"]);
            $tipoToma->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'Algo fallo'], 500);
        }
    }
    public function restaurarDato(TipoToma $TipoToma, Request $request)
    {

        $TipoToma = TipoToma::withTrashed()->findOrFail($request->id);

           // Verifica si el registro está eliminado
        if ($TipoToma->trashed()) {
            // Restaura el registro
            $TipoToma->restore();
            return response()->json(['message' => 'El tipo de toma ha sido restaurado.'], 200);
        }

    }
}
