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
        Schema::create('solicitud_cancelacion_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_solicitante');
            $table->unsignedBigInteger('id_caja');
            $table->string('folio');
            $table->enum('estado',['pendiente','rechazado','aprobado']);
            $table->unsignedBigInteger('id_revisor')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_cancelacion_pagos');
    }
};
