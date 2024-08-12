<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GiroComercialCatalogo;
use App\Http\Resources\GiroComercialCatalogoResource;
use App\Http\Requests\StoreGiroComercialCatalogoRequest;
use App\Http\Requests\UpdateGiroComercialCatalogoRequest;
use App\Services\Facturacion\GiroService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class GiroComercialCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', GiroComercialCatalogo::class);

        try {
            DB::beginTransaction();
            $giro = (new GiroService())->indexGiroService();
            DB::commit();
            return $giro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error durante la busqueda de giros.'
            ], 200);
        }

       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGiroComercialCatalogoRequest $request)
    {
        $this->authorize('create', GiroComercialCatalogo::class);

        try {
            $data = $request->validated();
            $nombre = $request->nombre;
            DB::beginTransaction();
            $giro = (new GiroService())->storeGiroService($data,$nombre);
            DB::commit();
            return $giro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al registrar el giro en el catalogo.'
            ], 200);
        }
            
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            DB::beginTransaction();
            $giro = (new GiroService())->showGiroService($id);
            DB::commit();
            return $giro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se encontro el giro comercial.'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGiroComercialCatalogoRequest $request, string $id)
    {
        $this->authorize('update', GiroComercialCatalogo::class);
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $giro = (new GiroService())->updateGiroService($data,$id);
            DB::commit();
            return $giro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se modifico el giro comercial'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete', GiroComercialCatalogo::class);
        try {
            DB::beginTransaction();
            $giro = (new GiroService())->destroyGiroService($id);
            DB::commit();
            return $giro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se borro el giro comercial.'
            ], 500);
        }
    }

    public function restaurarDato (GiroComercialCatalogo $catalogoGiros, Request $request)
    {

        try {
            $id = $request->id;
            DB::beginTransaction();
            $giro = (new GiroService())->restaurarDatoGiroService($id);
            DB::commit();
            return $giro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se restauro giro comercial.'
            ], 500);
        }
        
    }
}
