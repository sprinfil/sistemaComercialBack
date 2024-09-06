<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDatoFiscalRequest;
use App\Http\Requests\StoreUsuarioMoralRequest;
use App\Models\Usuario;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioMoralRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\CargoResource;
use App\Http\Resources\DatoFiscalResource;
use App\Http\Resources\TomaResource;
use App\Http\Resources\UsuarioResource;
use App\Services\Caja\CargoService;
use App\Services\ConsultarSaldoService;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $SaldoUsuarioService;

    public function __construct(UsuarioService $_ConsultarSaldoUsuario)
    {
        $this->SaldoUsuarioService = $_ConsultarSaldoUsuario;     
    }
    
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
                'error' => 'El usuario no se pudo crear.'.$ex,
                'restore' => false
            ], 200);
        }
    }
    
    public function storemoral(StoreUsuarioMoralRequest $request)
    {
        
        try{
            $data=$request->validated();
            DB::beginTransaction();
            $usuario = (new UsuarioService())->storeMoralService($data);
            DB::commit();
            return  $usuario;
        }
        catch(Exception $ex){
            DB::rollBack();
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
            $data = Usuario::ConsultarPorNombresCodigo($usuario);
     
            return response()->json($data);
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        }
    }
    public function showContacto(string $usuario)
    {
        try{
            $data = Usuario::ConsultarPorNombreContacto($usuario);
        return UsuarioResource::collection(
            $data
        );
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        }
    }
    public function showDireccion(string $usuario)
    {
        try{
            $data =(new UsuarioService())->DireccionToma($usuario);
            if ($data->isEmpty()){
                return response()->json(["message"=>"No existen tomas para esta dirección",],201);
            }
            else{
                return TomaResource::collection(
                    $data
                );
            }
        
        }
        catch(Exception $ex){
            return response()->json(['error' => 'error, consulta invalida'], 205);
        }
    }
    public function general(string $codigoUsuario)
    {
        $data =(new UsuarioService())->ConsultaGeneral($codigoUsuario);
        $numero_tomas=count($data->tomas);
       
        $datos=new UsuarioResource(
          $data
         );
    
    return response()->json(['usuario'=>$datos,'numero_tomas'=>$numero_tomas]);
    
        try{
            
           
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        }
        
        
    }

    public function showCodigo(string $usuario)
    {
        try{
            $data = Usuario::ConsultarPorCodigo($usuario);
            return UsuarioResource::collection(
                $data
            );
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios'], 200);
        }
    }

    public function showTomas(string $usuario)
    {
        try{
            $data =(new UsuarioService())->TomasUsuario($usuario);
            return TomaResource::collection(
                $data
            );
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron tomas'], 200);
        }  
    }

    public function showCodigoToma(string $usuario)
    {
        try{
            $data =(new UsuarioService())->UsuarioCodigoToma($usuario);
            if ($data->isEmpty()){
                return response()->json(["message"=>"No existen tomas para esta dirección",],201);
            }
            else{
                return TomaResource::collection(
                    $data
                );
            }
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se encontraron usuarios por este código de toma'], 200);
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
            $id = $request->id;
            DB::beginTransaction();
            $usuario = (new UsuarioService())->updateUsuarioService($data, $id);
            DB::commit();
            return $usuario;
        
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json(['error' => 'No se pudo modificar el usuario, introduzca datos correctos'], 200);
        }
    }
    
    public function updateMoral(UpdateUsuarioMoralRequest $request)
    {
        try{
            $data=$request->validated();
            $id = $request->id;
            DB::beginTransaction();
            $usuario = (new UsuarioService())->updateMoralUsuarioService($data,$id);
            DB::commit();
            return $usuario; 
        }
        catch(Exception $ex){
            DB::rollBack();
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
           $id = $request->id;
           DB::beginTransaction();
           $usuario = (new UsuarioService())->destroyUsuarioService($id);
           DB::commit();
           return $usuario;
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function restaurarDato(Usuario $Usuario, Request $request)
    {
        try {
           $id = $request->id;
           DB::beginTransaction();
           $usuario = (new UsuarioService())->restaurarDatoUsuarioService($id);
           DB::commit();
           return $usuario;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function datosFiscales($id)
    {
        try{
            DB::beginTransaction();
            $usuario = (new UsuarioService())->datosFiscalesUsuarioService($id);
            DB::commit();
            return $usuario;
        } catch(Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => 'error'], 500);
        } 
    }

    public function storeOrUpdateDatosFiscales(StoreDatoFiscalRequest $datoFiscalRequest, $id)
    {
        try{
                // Validar el request
                $validatedData = $datoFiscalRequest->validated();
                DB::beginTransaction();
                $datoFiscal = (new UsuarioService())->storeOrUpdateDatosFiscalesService($validatedData, $id);
                DB::commit();
                return $datoFiscal;

                return response()->json(['message' => 'error no se encontro usuario'], 500);
            }
            catch(Exception $ex) {
                DB::rollBack();
                return response()->json(['message' => 'error'.$ex], 500);
            } 
    }

    public function ConsultarSaldoUsuario($id) 
    {
        try {
            return response(
            $this->SaldoUsuarioService->TotalSaldoUsuario($id)
            );
        } catch (Exception $ex) {
             return response()->json([
                 'error' => 'No fue posible consultar el saldo' .$ex
             ], 500);
        }
    }

    public function consultarTodosCargosPendientes($id){
        try {
            return response(
                (new CargoService())->cargosDeTomasDeUsuario($id)
            );
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No fue posible consultar los cargos' .$ex
            ], 500);
        }
    }
}