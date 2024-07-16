<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'servicio_contratado',
        'colonia',
        'calle',
        'municipio',
        'localidad',
        'entre_calle1',
        'entre_calle2',
        'domicilio',
        'diametro_de_la_toma',
        'codigo_postal',
        'coordenada',
    ];

    // Toma asociada al contrato
    public function toma() : BelongsTo
    {
        return $this->belongsTo(Toma::class, 'id_toma');
    }

    // Servicio asociado a la toma
    public function servicio() : HasMany
    {
        return $this->hasMany(Servicio::class, 'id_contrato');
    }

    // Tipo de toma asociado al contrato
    public function tipoToma() : BelongsTo
    {
        return $this->belongsTo(TipoToma::class, 'tipo_toma', 'nombre');
    }
    public function cotizaciones(): HasMany
    {
        return $this->hasMany(cotizacion::class, 'id_contrato');
    }
    public function cotizacionesVigentes(): HasOne
    {
        $fecha=Carbon::now()->format('Y-m-d');
        return $this->HasOne(cotizacion::class, 'id_contrato')->where('vigencia','>=',$fecha);
    }
    public static function contratoRepetido($id_usuario, $servicios,$toma_id){
        $contratos= Contrato::where('id_usuario', $id_usuario)->where('id_toma',$toma_id)
        ->where('estatus', '!=', 'cancelado')
        ->where(function ($query) use ($servicios) {
            if (!empty($servicios)) {
                $query->whereIn('servicio_contratado', $servicios);
            }
        });
        return $contratos;
        
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

    public static function ConsultarPorFolio(string $folio, string $año){
        
        $data=Contrato::where('folio_solicitud','like','%'.$folio.'%/'.$año)->get();
        return $data;
        
    }
   
    
    
}
