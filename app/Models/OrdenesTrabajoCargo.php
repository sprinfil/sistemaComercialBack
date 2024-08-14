<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenesTrabajoCargo extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'id_orden_trabajo_catalogo',
        'id_concepto_catalogo',
    ];
    public function OrdenTrabajoCatalogo(): BelongsTo{
        return $this->belongsTo(OrdenTrabajoCatalogo::class,'id_orden_trabajo_catalogo');
    }
    public function OTConcepto(): BelongsTo{
        return $this->belongsTo(ConceptoCatalogo::class,'id_concepto_catalogo');
    }
}
