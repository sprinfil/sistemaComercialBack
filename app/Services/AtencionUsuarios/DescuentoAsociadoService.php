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
    {
        "id_descuento" : 1,
        "id_modelo" : 1,
        "modelo_dueno" : "toma",
        "id_registra" : 1,
        "vigencia" : "2024-08-16",
        "estatus" : "no_vigente",
        "folio" : "JASNDKAD"
    }

    Al registrar un descuento asociado
este se justifica con un documento que tiene un Folio. 

Se tiene que verificar que ese folio no este registrado con un
descuento activo y que la toma no tenga un descuento ya registrado

    */
    try {
        $folio = $data['folio'];
        $id_evidencia = $data['id_evidencia'];
        $descuentos = DescuentoAsociado::where('folio' , $folio)
        ->orWhere('id_evidencia' , $id_evidencia)->exists();
        $tomaexists = DescuentoAsociado::where('toma')->get();

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