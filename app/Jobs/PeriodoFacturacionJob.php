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
    protected $periodos;
    /**
     * Create a new job instance.
     */
    public function __construct($periodos)
    {
        $this->periodos = $periodos;
    }

    /**
     * Execute the job.
     */
    public function handle(FacturaService $factura): void
    {
        $factura->storeFacturaPeriodo($this->periodos);
    }
}
