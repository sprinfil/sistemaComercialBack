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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_periodo');
            $table->unsignedBigInteger('id_toma');
            $table->unsignedBigInteger('id_consumo');
            $table->unsignedBigInteger('id_tarifa_servicio');
            $table->double('monto');
            $table->date('fecha');
            $table->softDeletes();
            $table->timestamps();

            $table->index('id_toma');
            $table->index('id_periodo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
