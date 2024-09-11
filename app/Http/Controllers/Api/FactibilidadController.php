<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factibilidad;
use App\Http\Requests\StoreFactibilidadRequest;
use App\Http\Requests\UpdateFactibilidadRequest;
use App\Http\Resources\FactibilidadResource;
use App\Models\Contrato;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class FactibilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(FactibilidadResource::collection(
                Factibilidad::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar una factibilidad'.$e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Factibilidad $factibilidad , StoreFactibilidadRequest $request)
    {
        try{
            $data = $request->validated();
            $data['estado'] = 'pendiente';
            $data['agua_estado_factible'] = 'pendiente';
            $data['alc_estado_factible'] = 'pendiente';
            $data['san_estado_factible'] = 'pendiente';
            $factibilidad = Factibilidad::create($data);
            return response(new FactibilidadResource($factibilidad), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la factibilidad'.$e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $factibilidad = Factibilidad::findOrFail($id);
            return response(new FactibilidadResource($factibilidad), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la factibilidad'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFactibilidadRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $factibilidad = Factibilidad::findOrFail($id);

            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $path = $file->store('documentos', 'public'); // Guardar en el almacenamiento público
        
                // Agregar la ruta del archivo al campo correspondiente
                $data['documento'] = $path;
            }

            $factibilidad->update($data);
            $factibilidad->save();
            return response(new FactibilidadResource($factibilidad), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la factibilidad'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /*public function destroy(Factibilidad $factibilidad, $id)
    {
        try {
            $factibilidad = Factibilidad::findOrFail($id);
            $factibilidad->delete();
            return response("Factibilidad eliminada",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar la factibilidad'
            ], 500);
        }
    }*/
    
    /*public function restaurar (Factibilidad $factibilidad, Request $request)
    {
        try {
            $factibilidad = Factibilidad::withTrashed()->findOrFail($request->id);
            //Condicion para verificar si el registro esta eliminado
            if ($factibilidad->trashed()) {
                //Restaura el registro
                $factibilidad->restore();
                return response()->json(['message' => 'La factibilidad ha sido restaurada con exito' , 200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Hubo un error al restaurar la factibilidad'
            ]);
        }
    }*/

    public function generarConstanciaFactibilidadPdf($id)
    {
        try{
            $factibilidad = Factibilidad::findOrFail($id);
        }catch(Exception $ex){

        }

        $data = [
            'constancia_numero' => '123456',
            'calle' => 'Calle Principal',
            'numero_casa' => '12B',
            'calle_entre' => 'Calle 1',
            'calle_y' => 'Calle 2',
            'costo_factibilidad' => '$1,500',
            'recibo_numero' => '789123',
            'notificacion_calle' => 'Calle Secundaria',
            'nombre_solicitante' => 'Juan Pérez',
            'nombre_sistema' => 'Sistema Municipal',
        ];

        $pdf = FacadePDF::loadView('factibilidad', $data) // Nombre de la vista
            ->setPaper('A4', 'portrait')  // Tamaño de papel y orientación vertical
            ->setOption('margin-top', 0)
            ->setOption('margin-right', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0);

        return $pdf->download('constancia_factibilidad.pdf');  // Descarga directa del PDF
    }
}