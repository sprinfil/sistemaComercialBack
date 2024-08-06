<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use App\Http\Requests\StoreOperadorRequest;
use App\Http\Requests\UpdateOperadorRequest;
use App\Http\Resources\OperadorResource;
use App\Models\User;
use App\Services\Catalogos\OperadorCatalogoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Operador::class);
        try {
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->indexOperadorCatalogoService();
            return $operador;
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No fue posible consultar los operadores'
            ], 500);
        }

        
        

    }

    public function store_2(StoreOperadorRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->storeOperadorCatalogoService_2($data);
            DB::commit();
            return $operador;
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo guardar el operador.'
            ], 500);
        }
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
            $codEmpleado = $request->codigo_empleado;
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->storeOperadorCatalogoService($codEmpleado,$data);
            DB::commit();
            return $operador;
          
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo guardar el operador'
            ], 500);
        }
        
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
        $this->authorize('update', Operador::class);
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

    public function update_2(UpdateOperadorRequest $request, string $id_user, string $id_operador)
    {
        
            $data = json_decode($request->getContent(), true);
            $user = User::find($id_user);
            $user->name = $data["name"];
            $user->email = $data["email"];
            if ($data["password"]) {
                $user->password = bcrypt($data["password"]);
            }
            $user->save();

            $operador = Operador::find($id_operador);
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', Operador::class);
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
