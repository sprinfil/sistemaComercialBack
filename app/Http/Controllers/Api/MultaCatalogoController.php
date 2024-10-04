<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMultaCatalogo;
use App\Http\Requests\StoreMultaCatalogoRequest;
use App\Http\Requests\UpdateMultaCatalogo;
use App\Http\Requests\UpdateMultaCatalogoRequest;
use App\Http\Resources\MultaCatalogoResource;
use App\Models\MultaCatalogo;
use App\Services\Catalogos\MultaCatalogoService;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MultaCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $multacatalogo;

    /**
     * Constructor del controller
     */
    public function __construct(MultaCatalogoService $_multaCatalogo)
    {
        $this->multacatalogo = $_multaCatalogo;
    }
    public function index()
    {
        try {
            $multa = (new MultaCatalogoService())->index();
            return $multa;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'No se encontraron multas. ' .$ex->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMultaCatalogoRequest $request)
    {
        try{
            $data = $request->validated();
            DB::beginTransaction();
            $multacatalogo = $this->multacatalogo->store($data);
            DB::commit();
            return $multacatalogo;
            //return response(new MultaCatalogoResource($medidor), 201);
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo registrar la multa. ' .$e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $multacatalogo = (new MultaCatalogoService())->show($id);
            return $multacatalogo;
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'No se encontraron multas. ' .$ex->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMultaCatalogoRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $multa = $this->multacatalogo->update($data , $id);
            DB::commit();
            return $multa;
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al editar el descuento'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
