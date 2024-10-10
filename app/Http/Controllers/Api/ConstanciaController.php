<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConstanciaRequest;
use App\Services\AtencionUsuarios\ConstanciaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConstanciaController extends Controller
{
    public function store(StoreConstanciaRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $constancia = (new ConstanciaService())->storeService($data);
            DB::commit();
            return $constancia;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al registrar la solicitud de constancia'. $ex
            ], 500);
        }
    }

    //Este metodo solo se utiliza para probar el metodo de pago de constancia
    public function pagoConstancia(Request $request)
    {
        try {
            $data = $request->toArray();
            DB::beginTransaction();
            $constancia = (new ConstanciaService())->pagoConstanciaService(17);
            DB::commit();
            return $constancia;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al generar la constancia'. $ex
            ], 500);
        }
    }

    public function buscarRegistroConstancia(Request $request)
    {
        try {
            $data = $request->toArray();
            DB::beginTransaction();
            $constancia = (new ConstanciaService())->buscarRegistroConstanciaService($data);
            DB::commit();
            return $constancia;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al buscar las constancias'. $ex
            ], 500);
        }
    }

    public function EntregarConstancia(Request $request)
    {
        try {
            $data = $request->input('id');
            DB::beginTransaction();
            $constancia = (new ConstanciaService())->EntregarConstanciaService($data);
            DB::commit();
            return $constancia;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al buscar las constancias'. $ex
            ], 500);
        }
    }
}
