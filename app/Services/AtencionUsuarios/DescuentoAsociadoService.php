<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Resources\DescuentoAsociadoResource;
use App\Models\DescuentoAsociado;
use Exception;
use Illuminate\Http\Request;
class DescuentoAsociadoService {

public function index ()
{
    try {
        return response(DescuentoAsociadoResource::collection(
            DescuentoAsociado::all()
        ),200);
    } catch (Exception $ex) {
        return response()->json(['error' => 'Ocurrio un error al buscar los descuentos asociados'] , 500);
    }
}

public function store (array $data)
{
    /*  


    */
    try {
        $folio = $data['folio'];
        $id_evidencia = $data['id_evidencia'];
        $descuentos = DescuentoAsociado::where('folio' , $folio)
        ->orWhere('id_evidencia' , $id_evidencia)->exists();

            if ($descuentos) {
               return response()->json(['message'=>'Ya existe un folio o una evidencia'] , 400);
            }
            else{
                $descuento = DescuentoAsociado::create($data);
            }
        return response(new DescuentoAsociadoResource($descuento), 201);
    } catch (Exception $ex) {
       return response()->json(['error' => 'Ocurrio un error al registrar el descuento asociado. ' . $ex], 500);
    }
}


}