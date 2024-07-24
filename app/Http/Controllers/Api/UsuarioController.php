<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUsuarioMoralRequest;
use App\Models\Usuario;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioMoralRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\UsuarioResource;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return UsuarioResource::collection(
                Usuario::all()
            );
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Usuario $usuario,StoreUsuarioRequest $request)
    {
        
        
        try{
            $usuario=(new UsuarioService())->store($request->validated());
            return response(new UsuarioResource($usuario),201);
            /*
            $usuario = Usuario::withTrashed()->where('curp', $request['curp'])->orWhere('rfc', $request['rfc'])->orWhere('correo', $request['correo'])->first();

            //VALIDACION POR SI EXISTE
            if ($usuario) {
                if ($usuario->trashed()) {
                    return response()->json([
                        'message' => 'El usuario ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                        'restore' => true,
                        'usuario_id' => $usuario->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'El usuario ya existe.',
                    'restore' => false
                ], 200);
            }
            //si no existe el usuario lo crea
            if(!$usuario)
            {
                $usuario=Usuario::create($data);
                return response(new UsuarioResource($usuario),201);
            }
            */
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'El usuario no se pudo crear.',
                'restore' => false
            ], 200);
        }
   
        
        
    }
    //////
    public function storemoral(StoreUsuarioMoralRequest $request)
    {
        try{
            $data=$request->validated();
            $usuario = Usuario::withTrashed()->where('curp', $request['curp'])->orWhere('rfc', $request['rfc'])->orWhere('correo', $request['correo'])->first();

            //VALIDACION POR SI EXISTE
            if ($usuario) {
                if ($usuario->trashed()) {
                    return response()->json([
                        'message' => 'El usuario ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                        'restore' => true,
                        'usuario_id' => $usuario->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'El usuario ya existe.',
                    'restore' => false
                ], 200);
            }
            //si no existe el usuario lo crea
            if(!$usuario)
            {
                $usuario=Usuario::create($data);
                return response(new UsuarioResource($usuario),201);
            }
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'El usuario ya existe.',
                'restore' => false
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $usuario)
    {
        try{
            $data = Usuario::ConsultarPorNombres($usuario);
        return UsuarioResource::collection(
            $data
        );
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        }
        
        
    }
    public function showCURP(string $usuario)
    {
        try{
            $data = Usuario::ConsultarPorCurp($usuario);
            return UsuarioResource::collection(
                $data
            );
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        }   
    }
    public function showRFC(string $usuario)
    {
        try{
            $data = Usuario::ConsultarPorRfc($usuario);
            return UsuarioResource::collection(
                $data
            );
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        } 
    }
    public function showCorreo(string $usuario)
    {
        try{
            $data = Usuario::ConsultarPorCorreo($usuario);
            return UsuarioResource::collection(
                $data
            );
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request)
    {
        try{
            $data=$request->validated();
            $usuario=Usuario::find($request->id);
            $usuario->update($data);
            $usuario->save();
            return new UsuarioResource($usuario);
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo modificar el usuario, introduzca datos correctos'], 200);
        }
       
    }
    ///////
    public function updateMoral(UpdateUsuarioMoralRequest $request)
    {
        try{
            $data=$request->validated();
            $usuario=Usuario::find($request['id']);
            $usuario->update($data);
            $usuario->save();
            return new UsuarioResource($usuario);
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo modificar el usuario, introduzca datos correctos'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario, Request $request)
    {
        try
        {
            $usuario = Usuario::findOrFail($request["id"]);
            $usuario->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'error'], 500);
        }
    }
    public function restaurarDato(Usuario $Usuario, Request $request)
    {

        $Usuario = Usuario::withTrashed()->findOrFail($request->id);

           // Verifica si el registro está eliminado
        if ($Usuario->trashed()) {
            // Restaura el registro
            $Usuario->restore();
            return response()->json(['message' => 'El usuario ha sido restaurado.'], 200);
        }

    }
}
