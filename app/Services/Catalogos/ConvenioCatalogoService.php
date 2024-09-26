<?php
namespace App\Services\Catalogos;

use App\Http\Resources\ConvenioCatalogoResource;
use App\Http\Resources\ConvenioResource;
use App\Models\ConvenioCatalogo;
use COM;
use Exception;
use Illuminate\Http\Client\Request;

class ConvenioCatalogoService{


    public function indexconvenioCatalogoService()
    {
       
       try {
        return ConvenioCatalogoResource::collection(
            ConvenioCatalogo::orderby("id", "desc")->get()
        );
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron registros de convenios.'
        ], 200);
       }
        

    }

    public function storeConvenioCatalogoService(string $nombre,array $data)
    {

        try {       
        //Busca por nombre los eliminados
         $convenio = ConvenioCatalogo::withTrashed()->where('nombre' , $nombre)->first();
         if ($convenio) {
               if ($convenio->trashed()) {
               return response()->json([
                   'message' => 'El convenio ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                   'restore' => true,
                   'convenio_id' => $convenio->id
               ], 200);
               }
               return response()->json([
                  'message' => 'El convenio ya existe',
                  'restore' => false
               ], 200);
        }
        //Si no existe el convenio, lo crea
        if (!$convenio) {
            $convenio = ConvenioCatalogo::create($data);
            return response(new ConvenioCatalogoResource($convenio), 201);
        }
       
         } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar el convenio.'
             ], 200);
         }
              
    }

    

    public function updateConvenioCatalogoservice(array $data, string $id)
    {
               //pendiente validacion que permite solucionar repetidos por modificaciones sin update request
        try {            
                
                if ($data != null) {
                 
                    $convenioCatalogo = ConvenioCatalogo::find($id);
                    $convenioCatalogo->update($data);
                    $convenioCatalogo->save();
                    return new ConvenioCatalogoResource($convenioCatalogo);
                }
                else{
                    return response()->json([
                        'message' => 'Ocurrio un error al modificar el convenio.'
                    ], 200);

                }                         
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al modificar el convenio.'
            ], 200);
        }        
              
    }

    public function destroyConvenioCatalogoService(string $id)
    {      
        try {
            
            $convenioCatalogo = ConvenioCatalogo::findOrFail($id);
            $convenioCatalogo->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        } catch (Exception $ex) {
            return response()->json(['message' => 'Algo fallo'], 500);
        }      
    }

    public function restaurarConstanciaCatalogoServicio (string $id)
    {
        try {
            $convenioCatalogo = ConvenioCatalogo::withTrashed()->findOrFail($id);
            //Condicion para verificar si el registro esta eliminado
            if ($convenioCatalogo->trashed()) {
               //Restaura el registro
               $convenioCatalogo->restore();
               return response()->json(['message' => 'El convenio ha sido restaurado' , 200]);       
            }
          
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al restaurar la constancia.'
            ], 200);         
        }
       
    }

}