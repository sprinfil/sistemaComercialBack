<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTipoTomaAplicableRequest;
use App\Services\Toma\TipoTomaAplicableService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoTomaAplicableController extends Controller
{
    public function store(StoreTipoTomaAplicableRequest $request)
    {
        try{
            $data = $request->validated();
            DB::beginTransaction();
            $tipoTomaAplicable = (new TipoTomaAplicableService())->StoreService($data);
            DB::commit();
        return $tipoTomaAplicable;
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al registrar la configuracion del tipo de toma.'.$ex
            ]); 
        }
         
    }

    public function busquedaPorModelo(Request $request)
    {
        try {
            $data = $request->input('modelo_origen');
            DB::beginTransaction();
            $tipoTomaAplicable = (new TipoTomaAplicableService())->busquedaPorModeloService($data);
            DB::commit();
            return $tipoTomaAplicable;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al buscar los tipos de toma aplicables.'.$ex
            ]); 
        }
    }

    public function busquedaPorTipoToma(Request $request)
    {
        try {
            $data = $request->input('id_tipo_toma');
            DB::beginTransaction();
            $tipoTomaAplicable = (new TipoTomaAplicableService())->busquedaPorTipoTomaService($data);
            DB::commit();
            return $tipoTomaAplicable;

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al buscar los tipos de toma aplicables.'.$ex
            ]); 
        }
    }


    public function destroyTipoTomaAplicable(Request $request)
    {
        try {
            $data = $request->input('id');
            DB::beginTransaction();
            $tipoTomaAplicable = (new TipoTomaAplicableService())->destroyTipoTomaAplicableService($data);
            DB::commit();
            return $tipoTomaAplicable;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al eliminar el tipo de toma aplicable.'.$ex
            ]); 
        }
    }

    public function restaurarTipoTomaAplicable(Request $request)
    {
        try {
            $data = $request->input('id');
            DB::beginTransaction();
            $tipoTomaAplicable = (new TipoTomaAplicableService())->restaurarTipoTomaAplicableService($data);
            DB::commit();
            return $tipoTomaAplicable;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al restaurar el tipo de toma aplicable.'.$ex
            ]); 
        }
    }
}
