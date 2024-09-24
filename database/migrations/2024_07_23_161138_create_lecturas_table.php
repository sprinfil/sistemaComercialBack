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
        Schema::create('lecturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_operador');
            $table->unsignedBigInteger('id_toma');
            $table->unsignedBigInteger('id_periodo');
            $table->unsignedBigInteger('id_origen');
            $table->string('modelo_origen');
            $table->unsignedBigInteger('id_anomalia')->nullable();
            $table->integer('lectura');
            $table->string('comentario')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturas');
    }
};
