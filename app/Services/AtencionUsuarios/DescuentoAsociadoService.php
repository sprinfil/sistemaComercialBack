<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Requests\StoreDescuentoAsociadoRequest;
use App\Http\Resources\DescuentoAsociadoResource;
use App\Models\DescuentoAsociado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

public function store (array $request)
{
    try {
        /* 
        id_descuento
modelo_dueño
id_modelo
folio
id_evidencia
vigencia
estatus
id_registra (operador, quien registro)

crear un metodo para registrar esta informacion (basado en el Request de factibilidad)

            $table->unsignedBigInteger('id_descuento');
            $table->enum('modelo_dueno', ['toma' , 'usuario']);
            $table->unsignedBigInteger('id_modelo');
            $table->unsignedBigInteger('id_evidencia');
            $table->unsignedBigInteger('id_registra');
            $table->dateTime('vigencia');
            $table->enum('estatus' , ['si' , 'no']);

            {
    "id_descuento" : 1,
    "id_modelo" : 1,
    "modelo_dueno" : "toma",
    "id_evidencia" : 1,
    "id_registra" : 1,
    "vigencia" : "2024-08-16",
    "estatus" : "vigente",
    "folio" : "JASNDKAD"
}
        */
        $data = $request['id_descuento'];
        $data = $request['id_modelo'];
        $data = $request['modelo_dueno'];
        $data = $request['id_evidencia'];
        $data = $request['id_registra'];
        $data = $request['vigencia'];
        $data = $request['estatus'];
        $data = $request['folio'];
        $descuento = DescuentoAsociado::create($data);
        return response(new DescuentoAsociadoResource($descuento), 201);
    } catch (Exception $ex) {
       return response()->json(['error' => 'Ocurrio un error al registrar el descuento asociado. '], 500);
    }
}


}