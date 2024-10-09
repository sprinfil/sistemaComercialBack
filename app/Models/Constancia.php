<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Constancia extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "id_catalogo_constancia",
        "estado",
        "id_operador",
        "id_dueno",
        "modelo_dueno",
        "folio_solicitud",
    ];

    public function constanciaCatalogo () : BelongsTo
    {
        return $this->belongsTo(ConstanciaCatalogo::class , 'id_catalogo_constancia'); 
    }

    public function operador () : BelongsTo
    {
        return $this->belongsTo(Operador::class , 'id_operador'); 
    }

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_dueno', 'id_dueno'); 
    }

    public function archivo(): MorphOne
    {
        return $this->morphOne(Archivo::class, 'origen', 'modelo', 'id_modelo'); 
    }

    public static function darFolio()
    {
        $folio = Constancia::withTrashed()->max('folio_solicitud');


        if ($folio) {
            $num = intval(substr($folio, 0, 9)) + 1;
            switch (strlen(strval($num))) {
                case 1:
                    $num = "00000" . $num;
                    break;
                case 2:
                    $num = "00000" . $num;
                    break;
                case 3:
                    $num = "00000" . $num;
                    break;
                case 4:
                    $num = "00000" . $num;
                    break;
                case 5:
                    $num = "0000" . $num;
                    break;
                case 6:
                    $num = "000" . $num;
                    break;
                case 7:
                    $num = "00" . $num;
                    break;
                case 8:
                    $num = "0" . $num;
                    break;
            }
            $folio = $num . substr($folio, 9, 5);
        } else {
            $folio = "000000001/" . Carbon::now()->format('Y');
        }
        return $folio;
    }

    
}
