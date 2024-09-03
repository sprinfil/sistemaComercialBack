<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargosConveniado extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'id_cargo',
        'id_convenio'
    ];

    public function Convenio():BelongsTo{
        return $this->belongsTo(Convenio::class, "id_convenio", "id");
    }

    public function Cargo():BelongsTo{
        return $this->belongsTo(Cargo::class, "id_cargo", "id");
    }
}
