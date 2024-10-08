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

    public function pagoConstancia(Request $request)
    {
        try {
            $data = $request->toArray();
            DB::beginTransaction();
            $constancia = (new ConstanciaService())->pagoConstanciaService($data);
            DB::commit();
            return $constancia;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al generar la constancia'. $ex
            ], 500);
        }
    }
}
