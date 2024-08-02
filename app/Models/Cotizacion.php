<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="cotizaciones";
    protected $fillable=[
        'id_contrato',
        'vigencia',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class, 'id_contrato');
    }
    public function cotizacionesDetalles(): HasMany
    {
        return $this->hasMany(CotizacionDetalle::class, 'id_cotizacion');
    }
    public function TomaCotizada(): HasOneThrough
    {
        return $this->HasOneThrough(Toma::class, Contrato::class,'id','id','id_contrato','id_toma');
    }

   
}
