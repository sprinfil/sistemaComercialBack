<?php
return [
    'zona_horaria' => 'GMT-7',
    'valor_uma' => env('VALOR_UMA', 100),
    'concepto_contrato' => env('CONCEPTO_CONTRATO', 1),
    'concepto_servicio_agua' => env('CONCEPTO_SERVICIO_AGUA', 2),
    'concepto_servicio_alc'  => env('CONCEPTO_SERVICIO_ALC', 3),
    'concepto_servicio_san'  => env('CONCEPTO_SERVICIO_SAN', 4),
    'concepto_rezago'  => env('CONCEPTO_REZAGO', 5),
    'concepto_recargo'  => env('CONCEPTO_RECARGO', 6),

    /*
        TODO agregar al .env
        VALOR_UMA=108.57
        CONCEPTO_CONTRATO=1
        CONCEPTO_SERVICIO_AGUA=2
        CONCEPTO_RECONEXION=3
        CONCEPTO_SERVICIO_ALC=4
        CONCEPTO_SERVICIO_SAN=5
        CONCEPTO_REZAGO=6
        CONCEPTO_RECARGO=7
    */
];
