<?php
namespace App\Services\Facturacion;

use App\Http\Resources\GiroComercialCatalogoResource;
use App\Models\GiroComercialCatalogo;
use COM;
use Exception;
use Illuminate\Http\Client\Request;

class GiroService{


    public function indexGiroService()
    {
       
       try {
        return response(GiroComercialCatalogoResource::collection(
            GiroComercialCatalogo::orderby("id", "desc")->get()
        ),200);
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron registros de giros en el catalogo.'
        ], 200);
       }
        

    }

    public function storeGiroService(array $data, string $nombre)
    {
        try {       
             //Busca por nombre los eliminados
        $giro = GiroComercialCatalogo::withTrashed()->where('nombre' , $nombre)->first();
        if ($giro) {
            if ($giro->trashed()) {
                return response()->json([
                    'message' => 'El giro ya existe pero ha sido eliminado, ¿Desea restaurarlo?',
                    'restore' => true,
                    'giro_comercial_id' => $giro->id
                ], 200);
            }
            return response()->json([
                'message' => 'El giro comercial ya existe',
                'restore' => false
            ], 200);
        }
        //Si no existe el giro, la crea
        if (!$giro) {
            $giro = GiroComercialCatalogo::create($data);
            return response(new GiroComercialCatalogoResource($giro), 201);
        }
        } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar el giro en el catalogo.'
             ], 200);
        }              
    }

    

    public function updateGiroService(array $data, string $id)
    {
               
        try {            
            $girocomercial = GiroComercialCatalogo::findOrFail($id);
            $girocomercial->update($data);
            $girocomercial->save();
            return response(new GiroComercialCatalogoResource($girocomercial), 200);                      
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error durante la modificacion de giro.'
            ], 200);
        }        
              
    }

    public function showGiroService(string $id)
    {
        try {
            $girocomercial = GiroComercialCatalogo::findOrFail($id);
            return response(new GiroComercialCatalogoResource($girocomercial), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda del giro.'
            ], 500);
        }
    }

    public function destroyGiroService(string $id)
    {
        
        try {
            $girocomercial = GiroComercialCatalogo::findOrFail($id);
            $girocomercial->delete();
            return response("Giro comercial eliminado con exito",200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se borro el giro comercial.'
            ], 500);
        }
    }

    public function restaurarDatoGiroService (string $id)
    {
        try {
            $catalogoGiros = GiroComercialCatalogo::withTrashed()->findOrFail($id);
            //Condicion para verificar si el registro esta eliminado
            if ($catalogoGiros->trashed()) {
              //Restaura el registro
              $catalogoGiros->restore();
              return response()->json(['message' => 'El giro comercial ha sido restaurado' , 200]);
            }else{
                return response()->json([
                    'error' => 'No se restauro giro comercial.'
                ], 500);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se restauro giro comercial.'
            ], 500);
        }
        
    }

}