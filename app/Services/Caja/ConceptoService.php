<?php
namespace App\Services\Caja;

use App\Http\Requests\StoreConceptoCatalogoRequest;
use App\Http\Requests\UpdateConceptoCatalogoRequest;
use App\Http\Resources\ConceptoResource;
use App\Models\ConceptoCatalogo;
use App\Models\TarifaConceptoDetalle;
use App\Models\TipoToma;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ConceptoService{
    // metodo para obtener todos los conceptos registrados
    public function obtenerConceptos(): Collection
    {
        try{
            return ConceptoCatalogo::orderby("id", "desc")->with(['tarifas', 'ordenAsignada', 'conceptoResago'])->get();
        } catch(Exception $ex){
            throw $ex;
        }
    }

     // metodo para obtener todos los conceptos registrados
     public function obtenerConceptosCargables(): Collection
     {
         try{
            return ConceptoCatalogo::where('cargo_directo', 1)->orderby("id", "desc")->with('tarifas')->get();
         } catch(Exception $ex){
             throw $ex;
         }
     }

    // metodo para registrar un concepto
    public function registrarConcepto(StoreConceptoCatalogoRequest $request)
    {
        try{
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
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // metodo para buscar un concepto por id
    public function busquedaPorId($id): ConceptoCatalogo
    {
        try{
            return ConceptoCatalogo::findOrFail($id);
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // actualizar concepto
    public function modificarConcepto(UpdateConceptoCatalogoRequest $request)
    {
        try{
            $data = $request->validated();
        
            $concepto = ConceptoCatalogo::findOrFail($request["id"]);

            // Actualizar los datos del concepto
            $concepto->update($request->only(['nombre', 'descripcion', 'estado', 'prioridad_abono', 'genera_iva', "abonable", "tarifa_fija", "cargo_directo"]));

            // Actualizar tarifas
            $tarifas = $request->input('tarifas', []);
            foreach ($tarifas as $tarifaData) {
                $tarifa = TarifaConceptoDetalle::findOrNew($tarifaData['id_tipo_toma']);
                $tarifa->fill($tarifaData);
                $tarifa->id_concepto = $concepto->id;
                $tarifa->save();
            }

            // Devolver el concepto actualizado con sus tarifas
            return new ConceptoResource($concepto);
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // eliminar concepto
    public function eliminarConcepto($id)
    {
        try{
            $concepto = ConceptoCatalogo::findOrFail($id);
            $concepto->delete();
            return "Concepto eliminado con exito";
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // restaurar concepto
    public function restaurarConcepto(Request $request){
        try{
            $conceptoCatalogo = ConceptoCatalogo::withTrashed()->findOrFail($request->id);
            // Verifica si el registro está eliminado
            if ($conceptoCatalogo->trashed()) {
                // Restaura el registro
                $conceptoCatalogo->restore();
                return response()->json(['message' => 'El concepto ha sido restaurado.'], 200);
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }
}