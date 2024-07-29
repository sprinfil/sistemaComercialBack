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
            $table->unsignedInteger('id_caja');
            //$table->unsignedInteger('id_corte_caja');
            $table->decimal('total_pagado');
            $table->string('forma_pago');
            $table->date('fecha_pago');
            $table->enum("estado",['abonado','pendiente','cancelado']);
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
