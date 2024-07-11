<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use App\Http\Requests\StoreOperadorRequest;
use App\Http\Requests\UpdateOperadorRequest;
use App\Http\Resources\OperadorResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;

class OperadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Operador::class);
        return response(OperadorResource::collection(
            Operador::all()
        ), 200);
        //
    }

    public function store_2(StoreOperadorRequest $request)
    {
       
            $data = $request->validated();
            $user = new User();
            $user->name = $data["name"];
            $user->email = $data["email"];
            $user->password = bcrypt($data["password"]);
            $user->save();

            $operador = new Operador();
            $operador->id_user = $user->id;
            $operador->codigo_empleado = $data["codigo_empleado"];
            $operador->nombre = $data["nombre"];
            $operador->apellido_paterno = $data["apellido_paterno"];
            $operador->apellido_materno = $data["apellido_materno"];
            $operador->CURP = $data["CURP"];
            $operador->fecha_nacimiento = $data["fecha_nacimiento"];
            $operador->save();

            return response(new OperadorResource($operador), 201);
           
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Operador $operador, StoreOperadorRequest $request)
    {
        $this->authorize('create', Operador::class);

        try {
            //VALIDA EL STORE
            $data = $request->validated();

            //Busca por codigo de empleado a los eliminados
            $operador = Operador::withTrashed()->where('codigo_empleado', $request->input('codigo_empleado'))->first();

            //VALIDACION POR SI EXISTE
            if ($operador) {
                if ($operador->trashed()) {
                    return response()->json([
                        'message' => 'El operador ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                        'restore' => true,
                        'operador_id' => $operador->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'El concepto ya existe.',
                    'restore' => false
                ], 200);
            }
            //si no existe el concepto lo crea
            if (!$operador) {
                $operador = Operador::create($data);
                return response(new OperadorResource($operador), 201);
            }

            //Lo que ya tenia
            //$operador = Operador::create($data);
            // return response(new OperadorResource ($operador), 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el operador'
            ], 500);
        }
        // me falta el metodo me baso en el concepto controller
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $operador = Operador::findOrFail($id);
            return response(new OperadorResource($operador), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el operador'
            ], 500);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(UpdateOperadorRequest $request, string $id)
    {
        //Log::info("id");
        try {
            $data = $request->validated();
            $operador = Operador::findOrFail($id);
            $operador->update($data);
            $operador->save();
            return response(new OperadorResource($operador), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el operador'
            ], 500);
        }
     

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $operador = Operador::findOrFail($id);
            $operador->delete();
            return response("Operador eliminado con exito", 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el operador'
            ], 500);
        }
        //
    }
    public function restaurarOperador(Operador $operador, HttpRequest $request)
    {

        $operador = Operador::withTrashed()->findOrFail($request->id);

        // Verifica si el registro está eliminado
        if ($operador->trashed()) {

            // Restaura el registro
            $operador->restore();
            return response()->json(['message' => 'El operador ha sido restaurado.'], 200);
        }
    }
}
