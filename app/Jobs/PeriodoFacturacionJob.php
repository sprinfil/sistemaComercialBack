<?php

namespace App\Jobs;

use App\Services\Facturacion\FacturaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PeriodoFacturacionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    //protected $periodos;
    protected $periodo;
    protected $toma;
    protected $tarifaToma;
    protected $consumo;

    /**
     * Create a new job instance.
     */
    public function __construct($toma,$tarifaToma,$periodo,$consumo) //$periodos
    {
        //$this->periodos = $periodos;
        $this->periodo = $periodo;
        $this->toma = $toma;
        $this->tarifaToma = $tarifaToma;
        $this->consumo = $consumo;
  
    }

    /**
     * Execute the job.
     */
    public function handle(FacturaService $factura): void
    {
        $factura->facturar($this->toma,$this->tarifaToma,$this->periodo,$this->consumo);  //$this->periodos
    }

}
