<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConceptoAplicableRequest;
use App\Services\AtencionUsuarios\ConceptoAplicableService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConceptoAplicableController extends Controller
{
    public function store(StoreConceptoAplicableRequest $request)
    {
        try{
            $data = $request->validated();
            DB::beginTransaction();
            $conceptoAplicable = (new ConceptoAplicableService())->StoreService($data);
            DB::commit();
        return $conceptoAplicable;
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al registrar el la configuracion del concepto.'.$ex
            ]); 
        }
         
    }

    public function busquedaPorModelo(Request $request)
    {
        try {
            $data = $request->input('modelo');
            DB::beginTransaction();
            $conceptoAplicable = (new ConceptoAplicableService())->busquedaPorModeloService($data);
            DB::commit();
            return $conceptoAplicable;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al buscar los conceptos aplicables.'.$ex
            ]); 
        }
    }

    public function busquedaPorConcepto(Request $request)
    {
        try {
            $data = $request->input('id_concepto_catalogo');
            DB::beginTransaction();
            $conceptoAplicable = (new ConceptoAplicableService())->busquedaPorConceptoService($data);
            DB::commit();
            return $conceptoAplicable;

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al buscar los conceptos aplicables.'.$ex
            ]); 
        }
    }

    public function updateConceptoAplicable(Request $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $conceptoAplicable = (new ConceptoAplicableService())->updateConceptoAplicableService($data);
            DB::commit();
            return $conceptoAplicable;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al modificar el concepto aplicable.'.$ex
            ]); 
        }
    }

    public function deleteConceptoAplicable(Request $request)
    {
        try {
            $data = $request->input('id');
            DB::beginTransaction();
            $conceptoAplicable = (new ConceptoAplicableService())->deleteConceptoAplicableService($data);
            DB::commit();
            return $conceptoAplicable;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al eliminar el concepto aplicable.'.$ex
            ]); 
        }
    }
}
