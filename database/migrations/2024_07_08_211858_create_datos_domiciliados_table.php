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
        Schema::create('datos_domiciliados', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_toma')->unsigned()->default('1'); 
            $table->string('numero_cuenta');
            $table->string('fecha_vencimiento');
            $table->enum('tipo_tarjeta', ['credito', 'debito']);
            $table->decimal('limite_cobro', 8, 2);
            $table->string('domicilio_tarjeta');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_domiciliados');
    }
};
