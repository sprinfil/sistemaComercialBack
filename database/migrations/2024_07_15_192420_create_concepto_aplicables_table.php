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
        Schema::create('concepto_aplicables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_concepto_catalogo');
            $table->unsignedBigInteger('id_modelo');
            $table->string('modelo');
            $table->decimal('rango_minimo');
            $table->decimal('rango_maximo');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepto_aplicables');
    }
};
