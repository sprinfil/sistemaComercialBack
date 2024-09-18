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
            $table->enum('tipo_bonificacion', ['porcentual', 'fija']);
            $table->decimal('porcentaje_bonificable')->nullable();
            $table->decimal('monto_bonificable')->nullable();
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
