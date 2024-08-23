<?php
// apis catalogos

use App\Models\Toma;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Model;

if (!function_exists('helperGetOwner')) {
    function helperGetOwner($modelo, $id) : Model
    {
        try{
            if($modelo == 'usuario'){
                return Usuario::findOrFail($id);
            }else if($modelo == 'toma'){
                return Toma::findOrFail($id);
            }
        }
        catch(Exception $ex) {
            return null;
        }
    }
}

if (!function_exists('helperCalcularIVA')) {
    function helperCalcularIVA($monto)
    {
        try{
            $tarifaIVA=0.16;
            $total=$monto*$tarifaIVA;
            return $total;
        }
        catch(Exception $ex) {
            return null;
        }
    }
}