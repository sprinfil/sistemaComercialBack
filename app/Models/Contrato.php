<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'id_toma',
        'id_usuario',
        'folio_solicitud',
        'estatus',
        'nombre_contrato',
        'clave_catastral',
        'tipo_toma',
        'colonia',
        'calle',
        'entre_calle1',
        'entre_calle2',
        'domicilio',
        'diametro_de_la_toma',
        'codigo_postal',
        'coordenada',
    ];
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
    public function cotizaciones(): HasMany
    {
        return $this->hasMany(cotizacion::class, 'id_contrato');
    }
    public function cotizacionesVigentes(): HasMany
    {
        $fecha=Carbon::now()->format('Y-m-d');
        return $this->hasMany(cotizacion::class, 'id_contrato')->where('vigencia','<=',$fecha);
    }
    //genera el folio de la solicitud
    public static function darFolio(){
        $folio = Contrato::withTrashed()->max('folio_solicitud');

        
        if ($folio){
            $num=substr($folio,0,5)+1;
            switch(strlen(strval($num))){
                case 1:
                    $num="0000".$num;
                     break;
                case 2:
                    $num="000".$num;
                    break;
                case 3:
                    $num="00".$num;
                    break;
                case 4:
                    $num="0".$num;
                    break;
            }
            $folio=$num.substr($folio,5,5);
        }
        else{
            $folio="00001/".Carbon::now()->format('Y');
         
        }
        return $folio;
    }

    public static function ConsultarPorFolio(string $folio){
        
        $data=Contrato::where('folio_solicitud',$folio)->get();
        return $data;
        
    }
    
}
