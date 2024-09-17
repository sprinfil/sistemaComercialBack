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
        Schema::create('cfdis', function (Blueprint $table) {
            $table->id();
            $table->string('folio');
            $table->unsignedBigInteger('id_timbro');
            $table->enum('metodo', ['pendiente','masivo', 'directo'])->default('pendiente');
            $table->enum('estado', ['pendiente', 'fallido', 'realizado', 'cancelado'])->default('pendiente');
            $table->unsignedBigInteger('id_datos_fiscales');
            $table->string('documento')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cfdis');
    }
};
