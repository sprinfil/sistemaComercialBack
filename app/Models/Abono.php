<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abono extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_cargo",
        "id_origen",
        "modelo_origen",
        "total_abonado",
    ];

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_origen', 'id_origen');
    }

    public function cargo () : BelongsTo
    {
        return $this->belongsTo(Cargo::class,'id_cargo');
    }
}