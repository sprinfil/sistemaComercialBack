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
        Schema::create('corte_cajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_caja');
            $table->unsignedInteger('id_operador');
            $table->enum('estatus', ['aprobado', 'rechazado']);
            $table->decimal('total_registrado' , total: 8 , places: 2);
            $table->decimal('total_real', total: 8 , places:2);
            $table->decimal('total_efectivo_registrado', total: 8 , places:2);
            $table->decimal('total_efectivo_real', total: 8 , places:2);
            $table->decimal('total_tarjetas_registrado', total: 8 , places:2);
            $table->decimal('total_tarjetas_real', total: 8 , places:2);
            $table->decimal('total_cheques_registrado', total: 8 , places:2);
            $table->decimal('total_cheques_real', total: 8 , places:2);
            $table->enum('discrepancia' , ['si' , 'no']);
            $table->decimal('discrepancia_monto', total: 8 , places: 2);
            $table->dateTime('fecha_corte'); //datetime?         
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corte_cajas');
    }
};
