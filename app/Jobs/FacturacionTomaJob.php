<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FacturacionTomaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $toma;
    /**
     * Create a new job instance.
     */
    public function __construct($toma)
    {
        $this->toma = $toma;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
    }
}
