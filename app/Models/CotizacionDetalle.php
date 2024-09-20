<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class CotizacionDetalle extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'id_cotizacion',
        'id_sector',
        'id_concepto',
        'monto',
    ];
    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class, 'id_cotizacion');
    }
    public function contratoDetalle(): HasOneThrough
    {
        return $this->HasOneThrough(Contrato::class, Cotizacion::class,'id','id','id_cotizacion','id_contrato');
    }
    public function concepto():BelongsTo{
        return $this->belongsTo(ConceptoCatalogo::class, 'id_concepto');
    }
    #TODO
    /*
    public function sector(): BelongsTo
    {
        return $this->belongsTo(sector::class, 'id_sector');
    }
        */

    
}
