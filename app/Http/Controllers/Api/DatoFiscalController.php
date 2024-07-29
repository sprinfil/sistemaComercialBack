<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DatoFiscal;
use App\Http\Requests\StoreDatoFiscalRequest;
use App\Http\Requests\UpdateDatoFiscalRequest;
use App\Http\Resources\DatoFiscalResource;
use App\Models\Toma;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DatoFiscalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DatoFiscalResource::collection(
            DatoFiscal::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDatoFiscalRequest $request)
    {
        $data = $request->validated();
        $dato_fiscal = DatoFiscal::create($data);
        return response(new DatoFiscalResource($dato_fiscal), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DatoFiscal $dato_fiscal, Request $request)
    {
        //Falta consulta especifica 
        try {
            $dato_fiscal = DatoFiscal::findOrFail($request["id"]);
            return response(new DatoFiscalResource($dato_fiscal), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el Registro fiscal'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function showPorModelo(DatoFiscal $dato_fiscal, Request $request)
    {
        try {
            $id = $request->input('id_modelo');
            $modelo = $request->input('modelo');

            if($modelo == 'usuario'){
                $dato_fiscal = Usuario::findOrFail($id)->datos_fiscales;
            } else if($modelo == 'toma'){
                $dato_fiscal = Toma::findOrFail($id)->datos_fiscales;
            }

            if($dato_fiscal){
                return response(new DatoFiscalResource($dato_fiscal), 200);
            } else {
                return response()->json([
                    'error' => 'No se pudo encontrar el Registro fiscal2'
                ], 500);
            }  
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el Registro fiscal'.$e
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatoFiscalRequest $request, DatoFiscal $dato_fiscal)
    {
        $data = $request->validated();
        $dato_fiscal = DatoFiscal::find($request["id"]);
        $dato_fiscal->update($data);
        $dato_fiscal->save();
        return new DatoFiscalResource($dato_fiscal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatoFiscal $dato_fiscal, Request $request)
    {
        $dato_fiscal = DatoFiscal::find($request["id"]);
        $dato_fiscal->delete();
    }
}
