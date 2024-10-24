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
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_ruta');
            $table->unsignedInteger('id_tarifa');
            $table->string('nombre');
            $table->date('periodo'); //Solo importa mes y año
            $table->date('facturacion_fecha_inicio'); ///un mes
            $table->date('facturacion_fecha_final');
            $table->date('lectura_inicio');///menos del mes
            $table->date('lectura_final');
            $table->date('validacion_inicio');///menos del mes
            $table->date('validacion_final');
            $table->date('recibo_inicio')->nullable();///menos del mes
            $table->date('recibo_final')->nullable();
            $table->date('vigencia_recibo');
            $table->enum('estatus',['activo','cerrado','cancelado']);
            $table->softDeletes();
            $table->timestamps();
            //Si el mes se acaba automaticamente proceso para facturar tomas del mes pasado que no se hayan facturado.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
