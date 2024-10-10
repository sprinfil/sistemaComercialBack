<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MultaCatalogo extends Model
{
    use HasFactory;
    protected $table = "catalogo_multas";
    protected $fillable = [
        'nombre',
        'descripcion',
        'UMAS_min',
        'UMAS_max',
        'estatus'
    ];

    public function multas() : HasMany {
        return $this->hasMany(Multa::class, 'id_catalogo_multa' , 'id');
    }
}
