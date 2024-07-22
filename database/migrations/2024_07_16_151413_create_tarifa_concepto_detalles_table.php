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
        Schema::create('tarifa_concepto_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_tipo_toma');
            $table->unsignedBigInteger('id_concepto');
            $table->decimal('monto', total:9, places:2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifa_concepto_detalles');
    }
};
