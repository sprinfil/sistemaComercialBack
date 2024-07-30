<?php
namespace App\Services;

use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\Contrato;
use App\Models\Cotizacion;
use App\Models\Toma;
use Illuminate\Database\Eloquent\Collection;

class CotizacionService{
    public function GenerarCargo($conceptos): Cargo{
        
        return new Cargo();
    }
}