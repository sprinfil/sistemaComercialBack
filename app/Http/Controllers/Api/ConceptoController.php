<?php

namespace App\Http\Controllers\Api;

use App\Models\ConceptoCatalogo;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConceptoResource;
use App\Http\Requests\StoreConceptoCatalogoRequest;
use App\Http\Requests\UpdateConceptoCatalogoRequest;
use App\Models\TarifaConceptoDetalle;
use App\Models\TipoToma;
use Database\Factories\TarifaConceptoDetalleFactory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConceptoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', ConceptoCatalogo::class);
        return ConceptoResource::collection(
            ConceptoCatalogo::orderby("id", "desc")->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConceptoCatalogo $conceptoCatalogo, StoreConceptoCatalogoRequest $request)
    {
        $this->authorize('create', ConceptoCatalogo::class);
        //Valida el store
        $data = $request->validated();
        //Busca por nombre a los conceptos eliminados
        $conceptoCatalogo = ConceptoCatalogo::withTrashed()->where('nombre', $request->input('nombre'))->first();

        //Validacion en caso de que el concepto ya este registrado en le base de datos
        if ($conceptoCatalogo) {
            if ($conceptoCatalogo->trashed()) {
                return response()->json([
                    'message' => 'El concepto ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                    'restore' => true,
                    'concepto_id' => $conceptoCatalogo->id
                ], 200);
            }
            return response()->json([
                'message' => 'El concepto ya existe.',
                'restore' => false
            ], 200);
        }

        //Si el concepto no existe, lo crea
        if(!$conceptoCatalogo)
        {
            DB::beginTransaction();
            try{
                $conceptoCatalogo = ConceptoCatalogo::create($data);
                if(isset($data['tarifas']) && !is_null($data['tarifas'])){
                    foreach ($data['tarifas'] as $tarifas) {
                        $tarifa = new TarifaConceptoDetalle();
                        $tarifa->id_tipo_toma = $tarifas['id_tipo_toma'];
                        $tarifa->id_concepto = $conceptoCatalogo->id;
                        $tarifa->monto = $tarifas['monto'];
                        $tarifa->save();
                    }
                }else{
                    $tarifas_tipo = TipoToma::all();
                    foreach ($tarifas_tipo as $tipo) {
                        $tarifa = new TarifaConceptoDetalle();
                        $tarifa->id_tipo_toma = $tipo->id;
                        $tarifa->id_concepto = $conceptoCatalogo->id;
                        $tarifa->monto = 0;
                        $tarifa->save();
                    }
                }
                DB::commit();
                return new ConceptoResource($conceptoCatalogo);
            }catch(Exception $ex){
                DB::rollBack();
                return $ex;
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ConceptoCatalogo $conceptoCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConceptoCatalogoRequest $request, ConceptoCatalogo $conceptoCatalogo)
    {
        $this->authorize('update', ConceptoCatalogo::class);
        $data = $request->validated();
    
        $concepto = ConceptoCatalogo::findOrFail($request["id"]);

        // Actualizar los datos del concepto
        $concepto->update($request->only(['nombre', 'descripcion', 'estado', 'prioridad_abono', 'genera_iva']));

        // Actualizar tarifas
        $tarifas = $request->input('tarifas', []);
        foreach ($tarifas as $tarifaData) {
            $tarifa = TarifaConceptoDetalle::findOrNew($tarifaData['id']);
            $tarifa->fill($tarifaData);
            $tarifa->id_concepto = $concepto->id;
            $tarifa->save();
        }

        // Devolver el concepto actualizado con sus tarifas
        return new ConceptoResource($concepto);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConceptoCatalogo $conceptoCatalogo,Request $request)
    {
        $this->authorize('delete', ConceptoCatalogo::class);
        try
        {
            $conceptoCatalogo = ConceptoCatalogo::findOrFail($request->id);
            $conceptoCatalogo->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'Algo fallo'], 500);
        }
    }

    public function restaurarDato(ConceptoCatalogo $conceptoCatalogo, Request $request)
    {

        $conceptoCatalogo = ConceptoCatalogo::withTrashed()->findOrFail($request->id);

           // Verifica si el registro está eliminado
        if ($conceptoCatalogo->trashed()) {
            // Restaura el registro
            $conceptoCatalogo->restore();
            return response()->json(['message' => 'El concepto ha sido restaurado.'], 200);
        }

    }
}