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
        Schema::create('descuento_asociados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_descuento')->nullable();
            $table->unsignedBigInteger('id_modelo')->nullable();
            $table->enum('modelo_dueno', ['toma' , 'usuario'])->nullable();
            $table->unsignedBigInteger('id_evidencia')->nullable();
            $table->unsignedBigInteger('id_registra')->nullable();
            $table->dateTime('vigencia')->nullable();
            $table->enum('estatus' , ['vigente' , 'no_vigente'])->nullable();
            $table->string('folio')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descuento_asociados');
    }
};
