<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenesTrabajoEncadenada extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'id_OT_Catalogo_padre',
        'id_OT_Catalogo_encadenada',
    ];
    public function OrdenCatalogoPadre(): BelongsTo{
        return $this->belongsTo(OrdenTrabajoCatalogo::class,'id_OT_Catalogo_padre');
    }
    public function OrdenCatalogoEncadenadas():BelongsTo{
        return $this->belongsTo(OrdenTrabajoCatalogo::class,'id_OT_Catalogo_encadenada');
    }
}
