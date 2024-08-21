<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargoDirecto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "cargo_directo";
       
    protected $fillable = [
        "id_origen",
        "modelo_origen"
    ];

    public function cargos(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'origen', 'modelo_origen', 'id_origen');
    }

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_origen', 'id_origen');
    }
}