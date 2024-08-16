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
        Schema::create('tarifa_servicios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tarifa');
            $table->unsignedBigInteger('id_tipo_toma');
            $table->boolean('genera_iva'); //Alcantarillado y saneamiento genera IVA
            $table->enum('tipo_servicio', ['agua' , 'alcantarillado' , 'saneamiento']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifa_servicios');
    }
};
