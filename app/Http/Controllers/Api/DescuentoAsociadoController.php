<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DescuentoAsociado;
use App\Http\Requests\StoreDescuentoAsociadoRequest;
use App\Http\Requests\UpdateDescuentoAsociadoRequest;
use App\Http\Resources\DescuentoAsociadoResource;
use App\Models\Archivo;
use App\Services\AtencionUsuarios\DescuentoAsociadoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DescuentoAsociadoController extends Controller
{
    protected $descuentoasociado;

    /**
     * Constructor del controller
     */
    public function __construct(DescuentoAsociado $_descuentoasociado)
    {
        $this->descuentoasociado = $_descuentoasociado;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            DB::beginTransaction();
            $descuentoasociado = (new DescuentoAsociadoService())->index();
            DB::commit();
            return $descuentoasociado;
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los descuentos'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDescuentoAsociadoRequest $request)
    {
        try{
            $data = $request->validated();
            DB::beginTransaction();
            $descuentoAsociado = new DescuentoAsociadoService();
            $descuento = $descuentoAsociado->store($data);
            if (!$descuento) {
                return response()->json(['message' => 'Ya existe un descuento asociado, un folio o una evidencia'], 400);
            }
            if ($request->hasFile('evidencia')) {
                foreach ($request->file('evidencia') as $file) {
                    $descuentoAsociado->guardarArchivo($file, $data);
                }
            }
            DB::commit();
            return $descuento;
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo guardar el descuento ' .$e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $descuento = DescuentoAsociado::findOrFail($id);
            return response(new DescuentoAsociadoResource($descuento), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el descuento'
            ], 500);
        }
    }

    public function ConsultarPorTomaUsuario(Request $request)
    {
      try {
        $id_modelo = $request->input('id_modelo');
        $modelo_dueno = $request->input('modelo_dueno');
        //$data = DescuentoAsociado::findOrFail($id);
        $dueno = (new DescuentoAsociadoService())->filtro($id_modelo, $modelo_dueno);
        return $dueno;
      } catch (ModelNotFoundException $ex) {
        return response()->json([
            'error' => 'No se pudo consultar el modelo' .$ex
        ], 500);
      }  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDescuentoAsociadoRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $descuento = DescuentoAsociado::findOrFail($id);
            $descuento->update($data);
            $descuento->save();
            return response(new DescuentoAsociadoResource($descuento), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el descuento'
            ], 500);
        }
    }

    public function CancelarDescuento (UpdateDescuentoAsociadoRequest $request , $id)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $corte = (new DescuentoAsociadoService())->CancelarDescuento($data , $id);
            DB::commit();
            return $corte;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al cancelar el descuento. '.$ex
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $descuento = DescuentoAsociado::findOrFail($id);
            $descuento->delete();
            return response("Descuento eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el descuento'
            ], 500);
        }
    }

}
