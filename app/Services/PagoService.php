<?php
namespace App\Services;

use App\Models\Pago;

class PagoService{
    public function registraPago($conceptos): Pago{
        return new Pago();
    }
}