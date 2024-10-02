<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('folio');
            $table->unsignedInteger('id_caja');
            $table->unsignedBigInteger('id_dueno'); 
            $table->string('modelo_dueno');
            //$table->unsignedInteger('id_corte_caja');
            $table->decimal('total_pagado');
            //ticket
            $table->decimal('total_abonado')->nullable();
            $table->decimal('saldo_anterior')->nullable();
            $table->decimal('saldo_pendiente')->nullable();
            $table->decimal('saldo_a_favor')->nullable();
            $table->decimal('recibido')->nullable();
            $table->decimal('cambio')->nullable();
            //
            $table->enum("forma_pago",['efectivo','tarjeta_credito','tarjeta_debito','cheque','transferencia','documento'])->default('efectivo');
            $table->date('fecha_pago');
            $table->enum("estado",['abonado','pendiente','cancelado'])->default('pendiente');
            $table->string('referencia')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
