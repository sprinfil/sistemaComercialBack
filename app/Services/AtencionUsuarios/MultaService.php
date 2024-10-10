<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Resources\MultaResource;
use App\Models\Multa;
use App\Models\MultaCatalogo;
use App\Models\Toma;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MultaService{

public function index ()
{
    return response(MultaResource::collection(
        Multa::orderby('id', 'desc')->get()
    ), 200);
}

public function store ($data,$codigo_toma)
{
    try {
        //Para levantar una multa, se necesita buscar el codigo de la toma. 
        //$codigo_toma = $data['codigo_toma'];
        $cod_toma = Toma::where('codigo_toma' , $codigo_toma)->first();
        $catalogo_multa = MultaCatalogo::where('id', $data['id_catalogo_multa'])
        ->where('estatus' , 'activo')
        ->first();
        //Cuenta cuantas multas tiene registrada la toma. 
        //$total_multas = Multa::where('id_multado', $cod_toma->id)->count();
        if (!$catalogo_multa) {
            return response()->json([
                'message' => 'No se encontro la multa en el catalogo o la multa esta inactiva.'
            ], 404);
        }
        $monto = $data['monto'];
        if ($monto < $catalogo_multa->UMAS_min || $monto > $catalogo_multa->UMAS_max) {
            return response()->json([
                'message' => 'El monto ingresado está fuera del rango (' . $catalogo_multa->UMAS_min . ' - ' . $catalogo_multa->UMAS_max . ').'
         ], 422);
        }
        if ($cod_toma) {
            $data['id_multado'] = $cod_toma->id;
            $multa = Multa::create($data);
            return new MultaResource($multa);
        }
        else{
            return response()->json([
                'message' => 'No se encontro la toma.'
            ], 404);
        }

    } catch (Exception $ex) {
        return response()->json([
            'error' => 'Ocurrio un error al levantar la multa. ' .$ex->getMessage()
        ],500);
    }
}

public function show ($id)
{
    try {
        $multa = Multa::findOrFail($id);
        return response(new MultaResource($multa), 200);
    } catch (Exception $ex) {
        return response()->json([
            'error' => 'No se pudo encontrar la multa' .$ex->getMessage()
        ], 500);
    }
}

public function consultarmulta ($modelo_multado , $id_multado, $id_catalogo_multa, $codigo_usuario, $codigo_toma)
{
    try {
        $filtro = Multa::with('origen' , 'catalogo_multa')
        ->when($id_multado, function ($query, $id_multado) {
            return $query->where('id_multado', $id_multado);
        })
        ->when($modelo_multado, function ($query, $modelo_multado) {
            return $query->where('modelo_multado', $modelo_multado);
        })
        ->when($id_catalogo_multa, function ($query, $id_catalogo_multa){
            return $query->where('id_catalogo_multa' , $id_catalogo_multa);
        })
        ->orderBy('id', 'desc')
        ->get();
        $data = [];
        foreach ($filtro as $filt) {
            $id_toma = $filt->origen->id;
            if (!isset($data[$id_toma])) {
                $data[$id_toma] = [
                    'toma' => $filt->origen,
                    'multas' => []
                ];
            }
            $data [$id_toma]['multas'][] = [
                'nombre_multa_catalogo' => $filt->catalogo_multa->nombre,
                'descripcion_multa_catalogo' => $filt->catalogo_multa->descripcion,
                'id_multado' => $filt->id_multado,
                'id_catalogo_multa' => $filt->id_catalogo_multa,
                'modelo_multado' => $filt->modelo_multado,

            ];
            
        }
    $resultado = array_values($data);
    if (!$resultado) {
        return response()->json(['message' => 'No se encontraron resultados'], 404);
    } else {
        return $resultado;
    }
    } catch (ModelNotFoundException $ex) {
        return response()->json(['error' => 'Ocurrio un error al consultar la multa del usuario / toma' . $ex] , 500);
    }
}

}