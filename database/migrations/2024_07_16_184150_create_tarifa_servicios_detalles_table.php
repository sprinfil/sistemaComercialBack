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
        Schema::create('tarifa_servicios_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tarifa');
            $table->unsignedBigInteger('id_tipo_toma');
            $table->integer("rango");
            $table->decimal("agua", total: 9, places: 2);
            $table->decimal("alcantarillado", total: 9, places: 2);
            $table->decimal("saneamiento", total: 9, places: 2);
            $table->softDeletes();
            $table->timestamps();

            $table->index('id_tarifa');
            $table->index('id_tipo_toma');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifa_servicios_detalles');
    }
};
