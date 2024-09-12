<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "modelo",
        "id_modelo",
        "url",
        "tipo"
    ];

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo', 'id_modelo');
    }
}
