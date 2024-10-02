<?php
namespace App\Services\Catalogos;

use App\Http\Resources\AnomaliaCatalogoResource;
use App\Models\AnomaliaCatalogo;
use COM;
use Exception;
use Illuminate\Http\Client\Request;

class AnomaliaCatalogoService{


    public function indexAnomaliaCatalogo()
    {
       
       try {

        return AnomaliaCatalogoResource::collection(
            AnomaliaCatalogo::orderby("id", "desc")->get()
        );

       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron registros de anomalias.'
        ], 200);
       }
        

    }

    public function storeAnomaliaCatalogo(array $request)
    {

        //Busca por nombre los eliminados
        $anomalia = AnomaliaCatalogo::withTrashed()->where('nombre' , $request['nombre'])->first();
        
        if ($anomalia) {
            if ($anomalia->trashed()) {
                return response()->json([
                    'message' => 'La anomalia ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                    'restore' => true,
                    'anomalia_id' => $anomalia->id
                ], 200);
            }

            return response()->json([
                'message' => 'La anomalia ya existe.',
                'restore' => false
            ], 200);
        }

        //Si no existe la anomalia, la crea
        if (!$anomalia) {
            $anomalia = AnomaliaCatalogo::create($request);
            return response(new AnomaliaCatalogoResource($anomalia), 200);;
        }
    }

    public function showAnomaliaCatalogo(string $id)
    {
        try {
            $anomalia = AnomaliaCatalogo::findOrFail($id);
            return response(new AnomaliaCatalogoResource($anomalia), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se ha encontrado la anomalia.'
            ], 500);
        }
    }

    public function updateAnomaliaCatalogo(array $request, string $id)
    {
        
        
        try {
            $anomalia = AnomaliaCatalogo::find($id);
            
            $anomalia->update($request);
            $anomalia->save();
            return new AnomaliaCatalogoResource($anomalia);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se ha actualizado la anomalia.'
            ], 500);
        }        
        
        
    }

    public function destroyAnomaliaCatalogo(string $id)
    {
        try {
            $anomalia = AnomaliaCatalogo::find($id);
            $anomalia->delete();
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se ha actualizado la anomalia.'
            ]);
        }
        
    }

    public function restaurarAnomaliaCatalogo (string $id)
    {
        try {
            $catalogoAnomalia = AnomaliaCatalogo::withTrashed()->findOrFail($id);
           //Condicion para verificar si el registro esta eliminado
           if ($catalogoAnomalia->trashed()) {
              //Restaura el registro
              $catalogoAnomalia->restore();
              return response()->json(['message' => 'La anomalia ha sido restaurada' , 200]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se ha restaurado la anomalia.'
            ], 500);
        }
       
    }

}