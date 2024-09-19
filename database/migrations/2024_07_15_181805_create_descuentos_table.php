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
        Schema::create('descuentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_descuento_aplicado');
            $table->unsignedBigInteger('id_descuento_asociado');
            $table->decimal('monto_total');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descuentos');
    }
};
