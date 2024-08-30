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
        Schema::create('caja_catalogos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cuenta_contable');
            $table->integer('numero_caja');
            $table->string('nombre_caja');
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_catalogos');
    }
};
