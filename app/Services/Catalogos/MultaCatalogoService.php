<?php
namespace App\Services\Catalogos;

use App\Http\Resources\MultaCatalogoResource;
use App\Models\MultaCatalogo;
use Exception;
class MultaCatalogoService{

    public function index ()
    {
        return response(MultaCatalogoResource::collection(
            MultaCatalogo::orderby('id', 'desc')
            //->where('estatus' , 'activo')
            ->get()
        ), 200);
    }

    public function store (array $data)
    {
       //Store del catalogo de multas.
       try {
        $nombremultarepetido = MultaCatalogo::where('nombre' , $data['nombre'])->first();
        if ($nombremultarepetido) {
            return response()->json([
                'message' => 'El nombre de la multa ya existe. '
            ] , 409);
        }
        $multa = MultaCatalogo::create($data);
        return response(new MultaCatalogoResource($multa), 201);
       } catch (Exception $ex) {
        return response()->json(
            ['error' => 'No se pudo guardar la multa en el catalogo. ' . $ex->getMessage()]
         , 500);
       }
    }

    public function show ($id)
    {
        try {
            $multa = MultaCatalogo::findOrFail($id);
            return response(new MultaCatalogoResource($multa), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo encontrar la multa' .$ex->getMessage()
            ], 500);
        }
    }

    public function update (array $data , $id)
    {
        try {
            $multa = MultaCatalogo::find($id);
            if (!$multa) {
                return response()->json(['message' => 'no se encontraron resultados'] , 404);
            }
            else{
                $multa->update($data);
                $multa->save();
                return response(new MultaCatalogoResource($multa), 200);
            }
        } catch (Exception $ex) {
           return response()->json([
            'error' => 'No se pudo realizar la actualización de la multa. ' .$ex->getMessage()
           ], 500);
        }
    }


}