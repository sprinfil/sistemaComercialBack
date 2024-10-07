<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMultaRequest;
use App\Http\Requests\UpdateMultaRequest;
use App\Services\AtencionUsuarios\MultaService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class MultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     protected $multa;

     /**
      * Constructor del controller
      */
     public function __construct(MultaService $_multa)
     {
         $this->multa = $_multa;
     }
    public function index()
    {
        try {
            $multa = (new MultaService())->index();
            return $multa;
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'No se encontraron multas. ' .$ex->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMultaRequest $request)
    {
       
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $multacatalogo = (new MultaService())->show($id);
            return $multacatalogo;
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'No se encontraron multas. ' .$ex->getMessage()
            ], 500);
        }
    }

    public function multaporusuariotoma (Request $request)
    {
        try {
            $id_multado = $request->input('id_multado');
            $modelo_multado = $request->input('modelo_multado');
            //$data = DescuentoAsociado::findOrFail($id);
            $dueno = (new MultaService())->consultarporusuariotoma($id_multado , $modelo_multado);
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
    public function update(UpdateMultaRequest $request, string $id)
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
