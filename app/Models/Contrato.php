<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Point;

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
        'num_casa',
        'diametro_de_la_toma',
        'codigo_postal',
        'coordenada'
    ];

    // Toma asociada al contrato
    public function toma() : BelongsTo
    {
        return $this->belongsTo(Toma::class, 'id_toma');
    }
    public function usuario() : BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
    // Factibilidad asociada a un contrato
    public function factibilidad() : HasOne
    {
        return $this->hasOne(Factibilidad::class , 'id_contrato');
    }

    /*
    // Servicio asociado a la toma
    public function servicio() : HasMany
    {
        return $this->hasMany(Servicio::class, 'id_contrato');
    }
        */

    // Tipo de toma asociado al contrato
    public function tipoToma() : BelongsTo
    {
        return $this->belongsTo(TipoToma::class, 'tipo_toma', 'id');
    }
    public function cotizaciones(): HasMany
    {
        return $this->hasMany(Cotizacion::class, 'id_contrato');
    }
    public function cotizacionesVigentes(): HasOne
    {
        $fecha=Carbon::now()->format('Y-m-d');
        return $this->HasOne(Cotizacion::class, 'id_contrato')->where('vigencia','>=',$fecha);
    }

    public function cargos(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'origen', 'modelo_origen', 'id_origen');
    }
    public function cargosVigentes(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'origen', 'modelo_origen', 'id_origen')->where('estado','pendiente');
    }
    
    public function conceptoContrato() //Obtiene el concepto dependiendo del nombre del servicio
    {
        
        switch($this->servicio_contratado){
            case 'agua':
                $servicio=1; //id del concepto del contrato
                break;
            case 'alcantarillado y saneamiento':
                $servicio=2; //id del concepto del contrato
                break;
        }
        $conceptoContrato=ConceptoCatalogo::find($servicio);
        return $conceptoContrato;
    }
    public function tarifaContrato() //Obtiene el concepto dependiendo del nombre del servicio
    {
        
        $concepto=$this->conceptoContrato();
   
        $tipotoma=$this->tipoToma;
        $tarifa=TarifaConceptoDetalle::where('id_tipo_toma',$tipotoma['id'])->where('id_concepto',$concepto['id'])->first();
        return $tarifa;
    }

    public static function contratoRepetido($id_usuario, $servicios,$toma_id){
        $contratos= Contrato::where('id_toma',$toma_id)
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
            $num=intval(substr($folio,0,5))+1;
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

    public static function ConsultarPorFolio(string $folio, string $ano){
        
        $data=Contrato::with('usuario','toma')->where('folio_solicitud','like','%'.$folio.'%/'.$ano)->get();
        return $data;
        
    } 

    // Borrados y restores en cascada
    protected static function boot() //borrado en cascada
    {
        parent::boot();

        static::deleting(function ($parent) {
            // Soft delete related child models
            $parent->cotizaciones()->each(function ($child) {
                $child->delete();
            });
        });
        static::restoring(function ($parent) {
            $parent->cotizaciones()->withTrashed()->restore();
        });

    }  
}