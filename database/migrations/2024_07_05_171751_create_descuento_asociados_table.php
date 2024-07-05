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
            $table->bigInteger('id_usuario')->unsigned()->default('1'); 
            $table->bigInteger('id_toma')->unsigned()->default('1'); 
            $table->bigInteger('id_descuento')->unsigned()->default('1'); 
            $table->string('folio');
            $table->string('evidencia')->nullable();;
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
