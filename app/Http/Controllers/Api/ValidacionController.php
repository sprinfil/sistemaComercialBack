<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConsumoRequest;
use App\Services\Lectura\ValidacionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

class ValidacionController extends Controller
{
    protected $validacion;

    /**
     * Constructor del controller
     */
    public function __construct(ValidacionService $_validacion)
    {
        $this->validacion = $_validacion;
    }

    public function consumosperiodo ($id)
    {
        try {
            //$data = $request->validate();
            $anomalias = $this->validacion->consumosperiodo($id);
            return $anomalias;
        } catch (Exception $ex) {
           DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al mostrar las tomas. ' .$ex->getMessage()
            ], 500);
        }
    }

    public function registrarconsumo (Request $request)
    {
        try {
            $consumo = $request->input('consumo');
            $id_toma = $request->input('id_toma');
            $id_periodo = $request->input('id_periodo');
            $data = [
                'consumo' => $consumo,
            ];
            DB::beginTransaction();
            $registrarconsumo = $this->validacion->registrarconsumo($data , $id_toma , $id_periodo);
            DB::commit();
            return $registrarconsumo;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error registrar el consumo ' .$ex->getMessage()
            ], 500);
        }
    }
    
    public function promediar (Request $request)
    {
        try {
            $id_toma = $request->input('id_toma');
            $id_periodo = $request->input('id_periodo');
            DB::beginTransaction();
            $promediar = $this->validacion->promediar($id_toma, $id_periodo);
            DB::commit();
            return $promediar;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error generar el promedio ' .$ex->getMessage()
            ], 500);
        }
    }

    public function modificarconsumo ()
    {

    }

    public function modificarlectura ($id_periodo)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
